<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\OrderDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\AddressResource;
use Webkul\Admin\Http\Resources\CartResource;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Sales\Contracts\Order;
use Webkul\Sales\Repositories\OrderCommentRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\User\Models\Admin;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderCommentRepository $orderCommentRepository,
        protected CartRepository $cartRepository,
        protected CustomerGroupRepository $customerGroupRepository,
    ) {}

    /**
     * Seller panel may only access orders attributed to them and approved by super admin.
     */
    protected function assertSellerCanAccessOrder(Order $order): void
    {
        $user = auth()->guard('admin')->user();

        if (! $user instanceof Admin) {
            return;
        }

        $platformRoleIds = config('seller-panel.platform_admin_role_ids', []);
        $isPlatformAdmin = $user->role
            && $user->role->permission_type === 'all'
            && $platformRoleIds !== []
            && in_array((int) $user->role_id, $platformRoleIds, true);

        if ($isPlatformAdmin) {
            return;
        }

        if (! $order->seller_id) {
            abort(403);
        }

        if (
            (int) $order->seller_id !== (int) $user->id
            || $order->seller_approval_status !== 'approved'
        ) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(OrderDataGrid::class)->process();
        }

        $channels = core()->getAllChannels();

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        $paymentMethodOptions = $this->sellerPaymentMethodFilterOptions();

        return view('admin::sales.orders.index', compact('channels', 'groups', 'paymentMethodOptions'));
    }

    /**
     * Distinct payment methods used on this seller's orders (for seller panel filter dropdown).
     *
     * @return array<int, array{value: string, label: string}>
     */
    protected function sellerPaymentMethodFilterOptions(): array
    {
        $seller = auth()->guard('admin')->user();

        if (! $seller instanceof Admin) {
            return [['value' => '', 'label' => trans('admin::app.seller-panel.filters.all')]];
        }

        $platformRoleIds = config('seller-panel.platform_admin_role_ids', []);
        $isPlatformAdmin = $seller->role
            && $seller->role->permission_type === 'all'
            && $platformRoleIds !== []
            && in_array((int) $seller->role_id, $platformRoleIds, true);

        $methodsQuery = DB::table('order_payment')
            ->join('orders', 'orders.id', '=', 'order_payment.order_id');

        if (! $isPlatformAdmin) {
            $methodsQuery->where('orders.seller_id', $seller->id);
        }

        $methods = $methodsQuery
            ->distinct()
            ->pluck('order_payment.method')
            ->filter()
            ->values();

        $out = [['value' => '', 'label' => trans('admin::app.seller-panel.filters.all')]];

        foreach ($methods as $method) {
            $title = core()->getConfigData('sales.payment_methods.'.$method.'.title');

            $out[] = [
                'value' => $method,
                'label' => $title ?: (string) $method,
            ];
        }

        return $out;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(int $cartId)
    {
        $cart = $this->cartRepository->find($cartId);

        if (! $cart) {
            return redirect()->route('admin.sales.orders.index');
        }

        $addresses = AddressResource::collection($cart->customer->addresses);

        $cart = new CartResource($cart);

        return view('admin::sales.orders.create', compact('cart', 'addresses'));
    }

    /**
     * Store order
     */
    public function store(int $cartId)
    {
        $cart = $this->cartRepository->findOrFail($cartId);

        Cart::setCart($cart);

        if (Cart::hasError()) {
            return response()->json([
                'message' => trans('admin::app.sales.orders.create.error'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Cart::collectTotals();

        try {
            $this->validateOrder();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $cart = Cart::getCart();

        if (! in_array($cart->payment->method, ['cashondelivery', 'moneytransfer'])) {
            return response()->json([
                'message' => trans('admin::app.sales.orders.create.payment-not-supported'),
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = (new OrderResource($cart))->jsonSerialize();

        $order = $this->orderRepository->create($data);

        Cart::removeCart($cart);

        session()->flash('order', trans('admin::app.sales.orders.create.order-placed-success'));

        return new JsonResource([
            'redirect' => true,
            'redirect_url' => route('admin.sales.orders.view', $order->id),
        ]);
    }

    /**
     * Show the view for the specified resource.
     *
     * @return View
     */
    public function view(int $id)
    {
        $order = $this->orderRepository->findOrFail($id);

        $this->assertSellerCanAccessOrder($order);

        return view('admin::sales.orders.view', compact('order'));
    }

    /**
     * Reorder action for the specified resource.
     *
     * @return Response
     */
    public function reorder(int $id)
    {
        $order = $this->orderRepository->findOrFail($id);

        $this->assertSellerCanAccessOrder($order);

        $cart = Cart::createCart([
            'customer' => $order->customer,
            'is_active' => false,
        ]);

        Cart::setCart($cart);

        foreach ($order->items as $item) {
            try {
                Cart::addProduct($item->product, $item->additional);
            } catch (\Exception $e) {
                // do nothing
            }
        }

        return redirect()->route('admin.sales.orders.create', $cart->id);
    }

    /**
     * Cancel action for the specified resource.
     *
     * @return Response
     */
    public function cancel(int $id)
    {
        $order = $this->orderRepository->findOrFail($id);

        $this->assertSellerCanAccessOrder($order);

        $result = $this->orderRepository->cancel($id);

        if ($result) {
            session()->flash('success', trans('admin::app.sales.orders.view.cancel-success'));
        } else {
            session()->flash('error', trans('admin::app.sales.orders.view.create-error'));
        }

        return redirect()->route('admin.sales.orders.view', $id);
    }

    /**
     * Approve an order (Pending -> Processing).
     */
    public function approve(int $id)
    {
        $order = $this->orderRepository->findOrFail($id);

        $this->assertSellerCanAccessOrder($order);

        if ($order->status !== 'pending') {
            session()->flash('warning', 'Only pending orders can be approved.');

            return redirect()->route('admin.sales.orders.view', $id);
        }

        $seller = auth()->guard('admin')->user();

        $creditScore = (int) ($seller->credit_score ?? 0);
        $requiredCreditScore = (int) config('order-approval.min_credit_score', 100);

        if ($creditScore < $requiredCreditScore) {
            session()->flash('error', "Order approve nahi ho sakta: aap ka credit score {$requiredCreditScore}% se kam hai.");

            return redirect()->route('admin.sales.orders.view', $id);
        }

        $orderTotal = (float) ($order->grand_total ?? $order->base_grand_total ?? 0);
        $walletBufferPercent = (float) config('order-approval.wallet_buffer_percent', 0);
        $requiredBalance = $orderTotal + ($orderTotal * ($walletBufferPercent / 100));
        $availableBalance = (float) ($seller->wallet_balance ?? 0);

        if ($availableBalance < $requiredBalance) {
            session()->flash('error', 'Order approve nahi ho sakta: wallet me sufficient balance nahi hai.');

            return redirect()->route('admin.sales.orders.view', $id);
        }

        $this->orderRepository->update(['status' => 'processing'], $id);

        session()->flash('success', 'Order approved successfully.');

        return redirect()->route('admin.sales.orders.view', $id);
    }

    /**
     * Add comment to the order
     *
     * @return Response
     */
    public function comment(int $id)
    {
        $order = $this->orderRepository->findOrFail($id);

        $this->assertSellerCanAccessOrder($order);

        $validatedData = $this->validate(request(), [
            'comment' => 'required',
            'customer_notified' => 'sometimes|sometimes',
        ]);

        $validatedData['order_id'] = $id;

        Event::dispatch('sales.order.comment.create.before');

        $comment = $this->orderCommentRepository->create($validatedData);

        Event::dispatch('sales.order.comment.create.after', $comment);

        session()->flash('success', trans('admin::app.sales.orders.view.comment-success'));

        return redirect()->route('admin.sales.orders.view', $id);
    }

    /**
     * Result of search product.
     *
     * @return JsonResponse
     */
    public function search()
    {
        $orders = $this->orderRepository->scopeQuery(function ($query) {
            return $query->where('customer_email', 'like', '%'.urldecode(request()->input('query')).'%')
                ->orWhere('status', 'like', '%'.urldecode(request()->input('query')).'%')
                ->orWhere(DB::raw('CONCAT('.DB::getTablePrefix().'customer_first_name, " ", '.DB::getTablePrefix().'customer_last_name)'), 'like', '%'.urldecode(request()->input('query')).'%')
                ->orWhere('increment_id', request()->input('query'))
                ->orderBy('created_at', 'desc');
        })->paginate(10);

        foreach ($orders as $key => $order) {
            $orders[$key]['formatted_created_at'] = core()->formatDate($order->created_at, 'd M Y');

            $orders[$key]['status_label'] = $order->status_label;

            $orders[$key]['customer_full_name'] = $order->customer_full_name;
        }

        return response()->json($orders);
    }

    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    public function validateOrder()
    {
        $cart = Cart::getCart();

        if (! Cart::haveMinimumOrderAmount()) {
            throw new \Exception(trans('admin::app.sales.orders.create.minimum-order-error', [
                'amount' => core()->formatPrice(core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: 0),
            ]));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->shipping_address
        ) {
            throw new \Exception(trans('admin::app.sales.orders.create.check-shipping-address'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('admin::app.sales.orders.create.check-billing-address'));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->selected_shipping_rate
        ) {
            throw new \Exception(trans('admin::app.sales.orders.create.specify-shipping-method'));
        }

        if (! $cart->payment) {
            throw new \Exception(trans('admin::app.sales.orders.create.specify-payment-method'));
        }
    }
}
