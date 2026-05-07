#!/usr/bin/env python3
import argparse
import csv
import json
import os
import random
import sys
import time
import urllib.parse
import urllib.request
from collections import OrderedDict
from concurrent.futures import ThreadPoolExecutor, as_completed
from typing import Any, Dict, Iterable, List, Optional, Set, Tuple


DEFAULT_BASE_URL = "https://dhgyiu.com/wap/api"
DEFAULT_LIST_ENDPOINT = "sellerGoods!list.action"
DEFAULT_SELLER_ENDPOINT = "seller!list.action"
DEFAULT_DETAIL_ENDPOINT = "sellerGoods!info.action"


def post_json(
    url: str,
    payload: Dict[str, Any],
    timeout: int = 30,
    retries: int = 4,
    backoff: float = 1.5,
) -> Dict[str, Any]:
    """POST form-url-encoded payload and return parsed JSON."""
    last_error: Optional[Exception] = None
    for attempt in range(retries):
        try:
            data = urllib.parse.urlencode(payload).encode("utf-8")
            req = urllib.request.Request(
                url,
                data=data,
                method="POST",
                headers={
                    "User-Agent": "Mozilla/5.0",
                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                },
            )
            with urllib.request.urlopen(req, timeout=timeout) as response:
                body = response.read().decode("utf-8", "ignore")
            return json.loads(body)
        except Exception as exc:
            last_error = exc
            if attempt < retries - 1:
                sleep_s = (backoff ** attempt) + random.uniform(0.0, 0.35)
                time.sleep(sleep_s)
    raise RuntimeError(f"Failed request to {url} payload={payload}: {last_error}")


def flatten_json(obj: Any, prefix: str = "") -> Dict[str, Any]:
    out: Dict[str, Any] = {}
    if isinstance(obj, dict):
        if not obj and prefix:
            out[prefix] = ""
            return out
        for k, v in obj.items():
            nk = f"{prefix}.{k}" if prefix else str(k)
            out.update(flatten_json(v, nk))
        return out
    if isinstance(obj, list):
        if not obj:
            out[prefix] = "[]"
            return out
        for i, v in enumerate(obj):
            nk = f"{prefix}[{i}]"
            out.update(flatten_json(v, nk))
        return out
    out[prefix] = obj
    return out


def normalize_value(v: Any) -> str:
    if v is None:
        return ""
    if isinstance(v, bool):
        return "true" if v else "false"
    if isinstance(v, (int, float, str)):
        return str(v)
    return json.dumps(v, ensure_ascii=False, separators=(",", ":"))


def fetch_all_sellers(base_url: str, lang: str, page_size: int = 200) -> Dict[str, Dict[str, Any]]:
    """Fetch all sellers once and index by seller id."""
    url = f"{base_url.rstrip('/')}/{DEFAULT_SELLER_ENDPOINT}"
    sellers: Dict[str, Dict[str, Any]] = {}
    page_num = 1
    total_pages = None
    while True:
        response = post_json(url, {"pageNum": page_num, "pageSize": page_size, "lang": lang})
        if response.get("code") != "0" and response.get("code") != 0:
            raise RuntimeError(
                f"Seller list API failed for page {page_num}: "
                f"code={response.get('code')} msg={response.get('msg')}"
            )
        data = response.get("data") or {}
        page_info = data.get("pageInfo") or {}
        page_list = data.get("pageList") or []
        if total_pages is None:
            total_pages = int(page_info.get("totalPage") or 0) or None
        if not page_list:
            break
        for row in page_list:
            sid = str(row.get("id") or "")
            if sid:
                sellers[sid] = row
        if total_pages and page_num >= total_pages:
            break
        page_num += 1
    return sellers


def fetch_page(base_url: str, page_num: int, page_size: int, lang: str = "en") -> Tuple[List[Dict[str, Any]], Dict[str, Any]]:
    url = f"{base_url.rstrip('/')}/{DEFAULT_LIST_ENDPOINT}"
    payload = {"pageNum": page_num, "pageSize": page_size, "lang": lang}
    response = post_json(url, payload)
    if response.get("code") != "0" and response.get("code") != 0:
        msg = response.get("msg")
        raise RuntimeError(f"List API failed for page {page_num}: code={response.get('code')} msg={msg}")
    data = response.get("data") or {}
    page_info = data.get("pageInfo") or {}
    page_list = data.get("pageList") or []
    if not isinstance(page_list, list):
        page_list = []
    return page_list, page_info


def fetch_product_detail(base_url: str, seller_goods_id: str, lang: str = "en") -> Optional[Dict[str, Any]]:
    """Fetch product detail by sellerGoodsId."""
    url = f"{base_url.rstrip('/')}/{DEFAULT_DETAIL_ENDPOINT}"
    payload = {"sellerGoodsId": seller_goods_id, "lang": lang}
    response = post_json(url, payload)
    if response.get("code") != "0" and response.get("code") != 0:
        return None
    data = response.get("data")
    if isinstance(data, dict):
        return data
    return None


def is_missing_variant_fields(item: Dict[str, Any]) -> bool:
    attrs = item.get("attributes")
    selectable = item.get("canSelectAttributes")
    attrs_missing = attrs is None or attrs == "" or attrs == [] or attrs == {}
    selectable_missing = selectable is None or selectable == "" or selectable == [] or selectable == {}
    return attrs_missing and selectable_missing


def ordered_fieldnames(rows: Iterable[Dict[str, Any]]) -> List[str]:
    seen: "OrderedDict[str, None]" = OrderedDict()
    for row in rows:
        for k in row.keys():
            if k not in seen:
                seen[k] = None
    preferred_front = ["id", "goodsId", "sellerId", "name", "sellingPrice", "systemPrice"]
    ordered = [k for k in preferred_front if k in seen]
    ordered += [k for k in seen.keys() if k not in set(ordered)]
    return ordered


def export_all_products(
    base_url: str,
    output_csv: str,
    page_size: int,
    lang: str,
    max_pages: Optional[int],
    enrich_seller: bool,
    sleep_ms: int,
    workers: int,
    enrich_missing_variants_only: bool,
    detail_workers: int,
) -> Dict[str, Any]:
    all_rows: List[Dict[str, Any]] = []
    seen_ids: Set[str] = set()
    duplicate_rows = 0
    seller_cache: Dict[str, Dict[str, Any]] = {}
    detail_cache: Dict[str, Dict[str, Any]] = {}
    detail_requests = 0
    detail_enriched_rows = 0
    detail_failed = 0

    if enrich_seller:
        seller_cache = fetch_all_sellers(base_url, lang=lang, page_size=200)

    total_pages = None
    total_elements = None

    # Fetch first page to discover pagination metadata, then fan-out.
    page1_list, page1_info = fetch_page(base_url, 1, page_size, lang)
    total_pages = int(page1_info.get("totalPage") or 0) or None
    total_elements = int(page1_info.get("totalElements") or 0) or None
    target_last_page = total_pages or 1
    if max_pages:
        target_last_page = min(target_last_page, max_pages)

    pages_data: Dict[int, List[Dict[str, Any]]] = {1: page1_list}
    print(
        f"[page 1] rows={len(page1_list)} fetched totalPages={total_pages} totalElements={total_elements}",
        flush=True,
    )

    if target_last_page >= 2:
        with ThreadPoolExecutor(max_workers=max(1, workers)) as executor:
            future_map = {
                executor.submit(fetch_page, base_url, p, page_size, lang): p
                for p in range(2, target_last_page + 1)
            }
            for fut in as_completed(future_map):
                p = future_map[fut]
                page_list, _ = fut.result()
                pages_data[p] = page_list
                print(f"[page {p}] rows={len(page_list)} fetched", flush=True)
                if sleep_ms > 0:
                    time.sleep(sleep_ms / 1000.0)

    pages_processed = 0
    for page_num in range(1, target_last_page + 1):
        page_list = pages_data.get(page_num, [])
        pages_processed += 1
        if not page_list:
            continue

        # Fetch details only for rows where variant fields are missing.
        if enrich_missing_variants_only:
            missing_ids: List[str] = []
            for item in page_list:
                pid = str(item.get("id") or "")
                if pid and is_missing_variant_fields(item):
                    missing_ids.append(pid)

            pending_ids = [pid for pid in missing_ids if pid not in detail_cache]
            if pending_ids:
                if detail_workers <= 1:
                    for pid in pending_ids:
                        detail_requests += 1
                        detail_data = fetch_product_detail(base_url, pid, lang=lang)
                        if detail_data is None:
                            detail_failed += 1
                            continue
                        detail_cache[pid] = detail_data
                else:
                    with ThreadPoolExecutor(max_workers=max(1, detail_workers)) as executor:
                        future_map = {
                            executor.submit(fetch_product_detail, base_url, pid, lang): pid
                            for pid in pending_ids
                        }
                        for fut in as_completed(future_map):
                            pid = future_map[fut]
                            detail_requests += 1
                            detail_data = fut.result()
                            if detail_data is None:
                                detail_failed += 1
                                continue
                            detail_cache[pid] = detail_data

        for item in page_list:
            product_id = str(item.get("id") or "")
            if product_id and product_id in seen_ids:
                duplicate_rows += 1
                continue
            if product_id:
                seen_ids.add(product_id)

            if enrich_seller:
                seller_id = str(item.get("sellerId") or "")
                if seller_id and seller_id in seller_cache:
                    item["seller"] = seller_cache[seller_id]

            if enrich_missing_variants_only and product_id and is_missing_variant_fields(item):
                detail = detail_cache.get(product_id)
                if detail:
                    # Prefer detail payload because it contains richer variant fields.
                    item = {**item, **detail}
                    detail_enriched_rows += 1

            flat = flatten_json(item)
            normalized = {k: normalize_value(v) for k, v in flat.items()}
            all_rows.append(normalized)

    os.makedirs(os.path.dirname(output_csv), exist_ok=True)
    headers = ordered_fieldnames(all_rows)
    with open(output_csv, "w", encoding="utf-8", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=headers, extrasaction="ignore")
        writer.writeheader()
        writer.writerows(all_rows)

    summary = {
        "output_csv": output_csv,
        "rows_written": len(all_rows),
        "columns": len(headers),
        "deduped_rows_skipped": duplicate_rows,
        "pages_processed": pages_processed,
        "reported_total_pages": total_pages,
        "reported_total_elements": total_elements,
        "seller_cache_size": len(seller_cache),
        "detail_requests_made": detail_requests,
        "detail_enriched_rows": detail_enriched_rows,
        "detail_failed_count": detail_failed,
        "detail_cache_size": len(detail_cache),
    }
    return summary


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Export dhgyiu all products to flattened CSV.")
    parser.add_argument(
        "--base-url",
        default=DEFAULT_BASE_URL,
        help="API base URL (default: https://dhgyiu.com/wap/api)",
    )
    parser.add_argument(
        "--output",
        default="exports/dhgyiu_all_products_flattened_with_detail_variants.csv",
        help="Output CSV path.",
    )
    parser.add_argument("--page-size", type=int, default=100, help="Page size for list API.")
    parser.add_argument("--lang", default="en", help="Language parameter.")
    parser.add_argument("--max-pages", type=int, default=0, help="Debug limit; 0 means all pages.")
    parser.add_argument(
        "--no-seller-enrich",
        action="store_true",
        help="Disable seller info enrichment via seller!info.action.",
    )
    parser.add_argument("--sleep-ms", type=int, default=60, help="Sleep between pages in milliseconds.")
    parser.add_argument("--workers", type=int, default=16, help="Parallel workers for page fetching.")
    parser.add_argument(
        "--enrich-missing-variants-only",
        dest="enrich_missing_variants_only",
        action="store_true",
        default=True,
        help="Call detail API only for products with missing attributes and canSelectAttributes (default: enabled).",
    )
    parser.add_argument(
        "--no-enrich-missing-variants-only",
        dest="enrich_missing_variants_only",
        action="store_false",
        help="Disable detail enrichment for missing variant fields.",
    )
    parser.add_argument(
        "--detail-workers",
        type=int,
        default=8,
        help="Parallel workers for detail API calls.",
    )
    return parser.parse_args()


def main() -> int:
    args = parse_args()
    max_pages = args.max_pages if args.max_pages and args.max_pages > 0 else None
    summary = export_all_products(
        base_url=args.base_url,
        output_csv=args.output,
        page_size=args.page_size,
        lang=args.lang,
        max_pages=max_pages,
        enrich_seller=not args.no_seller_enrich,
        sleep_ms=args.sleep_ms,
        workers=args.workers,
        enrich_missing_variants_only=args.enrich_missing_variants_only,
        detail_workers=args.detail_workers,
    )
    print(json.dumps(summary, ensure_ascii=False, indent=2))
    return 0


if __name__ == "__main__":
    try:
        raise SystemExit(main())
    except KeyboardInterrupt:
        print("\nInterrupted by user.", file=sys.stderr)
        raise SystemExit(130)
