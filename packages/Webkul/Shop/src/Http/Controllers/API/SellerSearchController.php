<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Webkul\Shop\Http\Resources\SellerSearchResource;
use Webkul\User\Models\Admin;

class SellerSearchController extends APIController
{
    /**
     * Search seller accounts by name or email (for storefront header / discovery).
     */
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $term = trim((string) $request->query('query', ''));

        if (mb_strlen($term) < 2) {
            return response()->json(['data' => []]);
        }

        $nameOnly = $request->boolean('name_only');

        $sellers = Admin::query()
            ->where('status', 1)
            ->when($nameOnly, function ($query) use ($term) {
                $query->where('name', 'like', '%'.$term.'%');
            }, function ($query) use ($term) {
                $query->where(function ($qb) use ($term) {
                    $qb->where('name', 'like', '%'.$term.'%')
                        ->orWhere('email', 'like', '%'.$term.'%');
                });
            })
            ->orderBy('name')
            ->limit(15)
            ->get();

        return SellerSearchResource::collection($sellers);
    }
}
