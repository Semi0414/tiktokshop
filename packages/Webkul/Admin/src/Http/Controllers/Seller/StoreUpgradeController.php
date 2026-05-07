<?php

namespace Webkul\Admin\Http\Controllers\Seller;

use Illuminate\Support\Facades\Auth;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\User\Models\Admin;

class StoreUpgradeController extends Controller
{
    public function index()
    {
        /** @var Admin $seller */
        $seller = Auth::guard('admin')->user();

        return view('admin::seller.store-upgrade.index', [
            'seller' => $seller,
        ]);
    }
}
