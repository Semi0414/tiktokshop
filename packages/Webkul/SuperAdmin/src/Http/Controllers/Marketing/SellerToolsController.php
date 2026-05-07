<?php

namespace Webkul\SuperAdmin\Http\Controllers\Marketing;

use Illuminate\Support\Facades\Auth;
use Webkul\SuperAdmin\Http\Controllers\Controller;

class SellerToolsController extends Controller
{
    public function storeUpgrade()
    {
        $user = Auth::guard('superadmin')->user();

        return view('superadmin::marketing.seller-tools.store-upgrade', compact('user'));
    }

    public function sellerLevel()
    {
        $user = Auth::guard('superadmin')->user();

        return view('superadmin::marketing.seller-tools.seller-level', compact('user'));
    }
}
