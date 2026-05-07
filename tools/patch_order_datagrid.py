from pathlib import Path
p = Path("packages/Webkul/Admin/src/DataGrids/Sales/OrderDataGrid.php")
s = p.read_text(encoding="utf-8")
needle = "                'orders.cart_id as items',\n"
insert = needle + "                'orders.seller_make_order_at',\n                DB::raw('(SELECT COALESCE(AVG(ssp.commission_percent), 0) FROM '.DB::getTablePrefix().'order_items oi INNER JOIN '.DB::getTablePrefix().'seller_store_products ssp ON oi.product_id = ssp.product_id AND ssp.seller_id = '.DB::getTablePrefix().'orders.seller_id WHERE oi.order_id = '.DB::getTablePrefix().'orders.id AND oi.parent_id IS NULL) as seller_avg_commission'),\n"
if insert in s:
    print("already")
elif needle in s:
    s = s.replace(needle, insert, 1)
    p.write_text(s, encoding="utf-8")
    print("ok")
else:
    print("fail")
