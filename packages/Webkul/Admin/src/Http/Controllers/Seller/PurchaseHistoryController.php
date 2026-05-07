<?php

namespace Webkul\Admin\Http\Controllers\Seller;

use Illuminate\Support\Facades\Auth;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Models\Order;
use Webkul\User\Models\Admin;

class PurchaseHistoryController extends Controller
{
    /**
     * Purchase history (orders for this seller) — reference-style list.
     */
    public function index()
    {
        /** @var Admin $seller */
        $seller = Auth::guard('admin')->user();

        $orders = Order::query()
            ->where('seller_id', $seller->id)
            ->when(request()->filled('seller_ph_increment_id'), function ($q) {
                $v = request()->input('seller_ph_increment_id');

                $q->where('increment_id', 'like', '%'.addcslashes((string) $v, '%_\\').'%');
            })
            ->when(request()->filled('seller_ph_date_from'), fn ($q) => $q->whereDate('created_at', '>=', request()->input('seller_ph_date_from')))
            ->when(request()->filled('seller_ph_date_to'), fn ($q) => $q->whereDate('created_at', '<=', request()->input('seller_ph_date_to')))
            ->when(request()->filled('seller_ph_status') && request()->input('seller_ph_status') !== 'all', fn ($q) => $q->where('status', request()->input('seller_ph_status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin::seller.purchase-history.index', [
            'seller' => $seller,
            'orders' => $orders,
        ]);
    }
}
