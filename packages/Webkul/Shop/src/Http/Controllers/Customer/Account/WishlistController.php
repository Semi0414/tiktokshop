<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Illuminate\View\View;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Shop\Http\Controllers\Controller;

class WishlistController extends Controller
{
    public function __construct(protected WishlistRepository $wishlistRepository) {}

    /**
     * Displays the listing resources if the customer having items in wishlist.
     */
    public function index(): View
    {
        if (! core()->getConfigData('customer.settings.wishlist.wishlist_option')) {
            abort(404);
        }

        $wishlistItems = $this->wishlistRepository
            ->with(['product'])
            ->where([
                'channel_id' => core()->getCurrentChannel()->id,
                'customer_id' => auth()->guard('customer')->user()->id,
            ])
            ->get();

        return view('shop::customers.account.wishlist.index', compact('wishlistItems'));
    }
}
