<?php

namespace Webkul\SuperAdmin\Http\Controllers\Sales;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Sales\Repositories\OrderCommentRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\SuperAdmin\DataGrids\Sales\OrderDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Resources\AddressResource;
use Webkul\SuperAdmin\Http\Resources\CartResource;
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
     * Choose customer vs seller orders.
     *
     * @return View
     */
    public function landing()
    {
        return view('superadmin::sales.orders.landing');
    }

    /**
     * Display a listing of the resource (SSR table + sort/search like sellers index; export uses OrderDataGrid).
     *
     * @return View|BinaryFileResponse|JsonResponse
     */
    public function index(Request $request): View|BinaryFileResponse|JsonResponse
    {
        if ($request->boolean('export')) {
            return datagrid(OrderDataGrid::class)->process();
        }

        $prefix = DB::getTablePrefix();

        $query = DB::table('orders')
            ->leftJoin('seller', 'orders.seller_id', '=', 'seller.id')
            ->leftJoin('addresses as order_address_shipping', function ($leftJoin) {
                $leftJoin->on('order_address_shipping.order_id', '=', 'orders.id')
                    ->where('order_address_shipping.address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
            })
            ->leftJoin('addresses as order_address_billing', function ($leftJoin) {
                $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                    ->where('order_address_billing.address_type', OrderAddress::ADDRESS_TYPE_BILLING);
            })
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->select(
                'orders.id',
                DB::raw('GROUP_CONCAT('.DB::getTablePrefix().'order_payment.method SEPARATOR "|") as method'),
                'orders.increment_id',
                'orders.base_grand_total',
                'orders.created_at',
                'orders.channel_name',
                'orders.channel_id',
                'orders.status',
                'orders.customer_email',
                'seller.name as seller_name',
                DB::raw('CONCAT('.DB::getTablePrefix().'orders.customer_first_name, " ", '.DB::getTablePrefix().'orders.customer_last_name) as full_name'),
                DB::raw('CONCAT('.DB::getTablePrefix().'order_address_billing.city, ", ", '.DB::getTablePrefix().'order_address_billing.state,", ", '.DB::getTablePrefix().'order_address_billing.country) as location')
            )
            ->groupBy('orders.id');

        if ($request->filled('q')) {
            $term = '%'.addcslashes((string) $request->input('q'), '%_\\').'%';

            $query->where(function ($w) use ($term, $prefix) {
                $w->where('orders.increment_id', 'like', $term)
                    ->orWhere('orders.customer_email', 'like', $term)
                    ->orWhere(DB::raw('CONCAT('.$prefix.'orders.customer_first_name, " ", '.$prefix.'orders.customer_last_name)'), 'like', $term)
                    ->orWhere('seller.name', 'like', $term);
            });
        }

        $sort = $request->input('sort', 'created_at');
        $order = strtolower((string) $request->input('order', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = [
            'id', 'increment_id', 'created_at', 'status', 'base_grand_total',
            'channel_name', 'customer_email', 'full_name', 'seller_name',
        ];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $qualified = $this->ordersIndexSortQualified($sort);

        $query->orderBy($qualified, $order);

        $orders = $query->paginate(20)->withQueryString();

        $ordersWithItems = collect();

        if ($orders->isNotEmpty()) {
            $ordersWithItems = Order::query()
                ->with(['items.product.images'])
                ->whereIn('id', $orders->pluck('id')->all())
                ->get()
                ->keyBy('id');
        }

        $channels = core()->getAllChannels();

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('superadmin::sales.orders.index', compact(
            'channels',
            'groups',
            'orders',
            'ordersWithItems',
            'sort',
            'order'
        ));
    }

    /**
     * @return \Illuminate\Database\Query\Expression|string
     */
    protected function ordersIndexSortQualified(string $column)
    {
        $prefix = DB::getTablePrefix();

        return match ($column) {
            'id' => 'orders.id',
            'increment_id' => 'orders.increment_id',
            'status' => 'orders.status',
            'base_grand_total' => 'orders.base_grand_total',
            'channel_id' => 'orders.channel_id',
            'customer_email' => 'orders.customer_email',
            'channel_name' => 'orders.channel_name',
            'created_at' => 'orders.created_at',
            'full_name' => DB::raw('CONCAT('.$prefix.'orders.customer_first_name, " ", '.$prefix.'orders.customer_last_name)'),
            'location' => DB::raw('CONCAT('.$prefix.'order_address_billing.city, ", ", '.$prefix.'order_address_billing.state,", ", '.$prefix.'order_address_billing.country)'),
            'seller_name' => 'seller.name',
            'method' => DB::raw('GROUP_CONCAT('.$prefix.'order_payment.method SEPARATOR "|")'),
            'items' => 'orders.cart_id',
            default => str_starts_with((string) $column, 'orders.')
                ? $column
                : (Schema::hasColumn((new Order)->getTable(), (string) $column)
                    ? 'orders.'.$column
                    : 'orders.created_at'),
        };
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
            return redirect()->route('superadmin.sales.orders.customers.index');
        }

        $addresses = AddressResource::collection($cart->customer->addresses);

        $cart = new CartResource($cart);

        return view('superadmin::sales.orders.create', compact('cart', 'addresses'));
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
                'message' => trans('superadmin::app.sales.orders.create.error'),
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
                'message' => trans('superadmin::app.sales.orders.create.payment-not-supported'),
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = (new OrderResource($cart))->jsonSerialize();

        $order = $this->orderRepository->create($data);

        Cart::removeCart($cart);

        session()->flash('order', trans('superadmin::app.sales.orders.create.order-placed-success'));

        return new JsonResource([
            'redirect' => true,
            'redirect_url' => route('superadmin.sales.orders.view', $order->id),
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

        $sellers = Admin::query()->orderBy('name')->get();

        return view('superadmin::sales.orders.view', compact('order', 'sellers'));
    }

    /**
     * Reorder action for the specified resource.
     *
     * @return Response
     */
    public function reorder(int $id)
    {
        $order = $this->orderRepository->findOrFail($id);

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

        return redirect()->route('superadmin.sales.orders.create', $cart->id);
    }

    /**
     * Cancel action for the specified resource.
     *
     * @return Response
     */
    public function cancel(int $id)
    {
        $result = $this->orderRepository->cancel($id);

        if ($result) {
            session()->flash('success', trans('superadmin::app.sales.orders.view.cancel-success'));
        } else {
            session()->flash('error', trans('superadmin::app.sales.orders.view.create-error'));
        }

        return redirect()->route('superadmin.sales.orders.view', $id);
    }

    /**
     * Approve an order (Pending -> Processing).
     */
    public function approve(int $id)
    {
        $order = $this->orderRepository->findOrFail($id);

        if ($order->status !== 'pending') {
            session()->flash('warning', 'Only pending orders can be approved.');

            return redirect()->route('superadmin.sales.orders.view', $id);
        }

        $this->orderRepository->update(['status' => 'processing'], $id);

        session()->flash('success', 'Order approved successfully.');

        return redirect()->route('superadmin.sales.orders.view', $id);
    }

    /**
     * Add comment to the order
     *
     * @return Response
     */
    public function comment(int $id)
    {
        $validatedData = $this->validate(request(), [
            'comment' => 'required',
            'customer_notified' => 'sometimes|sometimes',
        ]);

        $validatedData['order_id'] = $id;

        Event::dispatch('sales.order.comment.create.before');

        $comment = $this->orderCommentRepository->create($validatedData);

        Event::dispatch('sales.order.comment.create.after', $comment);

        session()->flash('success', trans('superadmin::app.sales.orders.view.comment-success'));

        return redirect()->route('superadmin.sales.orders.view', $id);
    }

    /**
     * Attribute an order to a seller for marketplace visibility and approval.
     */
    public function assignSeller(Request $request, int $id): RedirectResponse
    {
        $order = $this->orderRepository->findOrFail($id);

        $data = $request->validate([
            'seller_id' => 'nullable|integer|exists:seller,id',
        ]);

        $sellerId = $data['seller_id'] ?? null;

        $order->seller_id = $sellerId;
        $order->seller_approval_status = $sellerId ? 'pending' : null;
        $order->save();

        session()->flash('success', trans('superadmin::app.sales.orders.view.seller-assigned-success'));

        return redirect()->route('superadmin.sales.orders.view', $id);
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
            throw new \Exception(trans('superadmin::app.sales.orders.create.minimum-order-error', [
                'amount' => core()->formatPrice(core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: 0),
            ]));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->shipping_address
        ) {
            throw new \Exception(trans('superadmin::app.sales.orders.create.check-shipping-address'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('superadmin::app.sales.orders.create.check-billing-address'));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->selected_shipping_rate
        ) {
            throw new \Exception(trans('superadmin::app.sales.orders.create.specify-shipping-method'));
        }

        if (! $cart->payment) {
            throw new \Exception(trans('superadmin::app.sales.orders.create.specify-payment-method'));
        }
    }
}
