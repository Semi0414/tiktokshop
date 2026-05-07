<?php

namespace Webkul\SuperAdmin\Http\Controllers\Sellers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webkul\Sales\Models\Order;
use Webkul\SuperAdmin\DataGrids\Sellers\View\InvoiceDataGrid as SellerViewInvoiceDataGrid;
use Webkul\SuperAdmin\DataGrids\Sellers\View\OrderDataGrid as SellerViewOrderDataGrid;
use Webkul\SuperAdmin\DataGrids\Sellers\View\ReviewDataGrid as SellerViewReviewDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Requests\MassDestroyRequest;
use Webkul\SuperAdmin\Http\Requests\MassUpdateRequest;
use Webkul\SuperAdmin\Services\SuperAdminEmailService;
use Webkul\User\Models\Admin;
use Webkul\User\Models\SellerNote;
use Webkul\User\Repositories\AdminRepository;
use Webkul\User\Repositories\RoleRepository;
use Webkul\User\Services\SellerJoinSellerService;
use Webkul\User\Support\SellerCommissionPercentRules;

class SellerController extends Controller
{
    public const ORDERS = 'orders';

    public const INVOICES = 'invoices';

    public const REVIEWS = 'reviews';

    public function __construct(
        protected AdminRepository $adminRepository,
        protected RoleRepository $roleRepository
    ) {}

    /**
     * @return array<string, mixed>
     */
    protected function buildSellerVuePayload(Admin $seller): array
    {
        if (! $seller->relationLoaded('role')) {
            $seller->load('role');
        }

        $parts = preg_split('/\s+/', trim((string) $seller->name), 2, PREG_SPLIT_NO_EMPTY);

        return [
            'id' => $seller->id,
            'first_name' => $parts[0] ?? '',
            'last_name' => $parts[1] ?? '',
            'email' => $seller->email,
            'phone' => null,
            'gender' => null,
            'date_of_birth' => null,
            'status' => (bool) $seller->status,
            'is_suspended' => false,
            'customer_group_id' => null,
            'wallet_balance' => $seller->wallet_balance ?? 0,
            'credit_score' => $seller->credit_score ?? 100,
            'role_id' => $seller->role_id,
            'group' => $seller->relationLoaded('role') && $seller->role
                ? ['name' => $seller->role->name]
                : null,
            'max_visible_products' => $seller->max_visible_products ?? 0,
            'referral_code' => Schema::hasColumn('seller', 'referral_code') ? (string) ($seller->referral_code ?? '') : '',
            'seller_level' => Schema::hasColumn('seller', 'seller_level')
                ? SellerCommissionPercentRules::normalizeLevel($seller->seller_level)
                : 'Beginner',
            'addresses' => [],
        ];
    }

    public function index(Request $request): View
    {
        $roles = $this->roleRepository->all();

        $query = Admin::query()->with('role');

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'seller_id')) {
            $query->withCount('sellerOrders');
        }

        if ($request->filled('q')) {
            $term = '%'.addcslashes((string) $request->input('q'), '%_\\').'%';

            $query->where(function ($w) use ($term) {
                $w->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        $sort = $request->input('sort', 'id');
        $order = strtolower((string) $request->input('order', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['id', 'name', 'email', 'status'];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        $query->orderBy($sort, $order);

        $sellers = $query->paginate(20)->withQueryString();

        $revenueBySellerId = [];

        if (
            $sellers->isNotEmpty()
            && Schema::hasTable('orders')
            && Schema::hasColumn('orders', 'seller_id')
        ) {
            $ids = $sellers->pluck('id')->all();

            $revenueBySellerId = Order::query()
                ->whereIn('seller_id', $ids)
                ->whereNotIn('status', [Order::STATUS_CANCELED, Order::STATUS_CLOSED])
                ->groupBy('seller_id')
                ->selectRaw('seller_id, SUM(base_grand_total_invoiced) as revenue_total')
                ->pluck('revenue_total', 'seller_id')
                ->map(fn ($v) => (float) $v)
                ->all();
        }

        return view('superadmin::sellers.index', compact(
            'roles',
            'sellers',
            'sort',
            'order',
            'revenueBySellerId'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:seller,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
            'status' => 'sometimes|boolean',
        ]);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role_id' => $request->input('role_id'),
            'status' => $request->boolean('status', true) ? 1 : 0,
            'api_token' => Str::random(80),
            'seller_approval_status' => 'approved',
            'max_visible_products' => 0,
        ];

        if (Schema::hasColumn('seller', 'seller_level')) {
            $data['seller_level'] = 'Beginner';
        }

        Event::dispatch('user.admin.create.before');

        $seller = $this->adminRepository->create($data);

        if (Schema::hasColumn('seller', 'referral_code') && empty($seller->referral_code)) {
            $seller->referral_code = app(SellerJoinSellerService::class)->generateUniqueReferralCode();
            $seller->save();
        }

        Event::dispatch('user.admin.create.after', $seller);

        return new JsonResponse([
            'data' => $seller,
            'message' => trans('superadmin::app.sellers.index.create.create-success'),
        ]);
    }

    public function show($id): View|BinaryFileResponse|JsonResponse
    {
        request()->merge([
            'seller_id' => (int) $id,
        ]);

        if (request()->boolean('export')) {
            return match (request()->query('dg')) {
                'orders' => $this->resolveNestedPaginationExport(SellerViewOrderDataGrid::class, 'seller_orders'),
                'invoices' => $this->resolveNestedPaginationExport(SellerViewInvoiceDataGrid::class, 'seller_invoices'),
                'reviews' => $this->resolveNestedPaginationExport(SellerViewReviewDataGrid::class, 'seller_reviews'),
                default => abort(404),
            };
        }

        $seller = Admin::query()
            ->with(['role', 'sellerOrders', 'sellerInvoices', 'sellerNotes'])
            ->findOrFail($id);

        $sellerApplication = null;
        $applicationCountryName = null;

        if (Schema::hasTable('seller_applications')) {
            $sellerApplication = DB::table('seller_applications')
                ->where('seller_id', $seller->id)
                ->orderByDesc('id')
                ->first();

            if ($sellerApplication && ! empty($sellerApplication->country)) {
                $match = collect(core()->countries())->firstWhere('code', $sellerApplication->country);
                $applicationCountryName = $match ? $match->name : $sellerApplication->country;
            }
        }

        $sellerForVue = $this->buildSellerVuePayload($seller);

        $welcomeSubjectDefault = old('subject', trans('superadmin::app.sellers.view.welcome-email-default-subject'));
        $welcomeMessageDefault = old('message', trans('superadmin::app.sellers.view.welcome-email-default-message'));

        return view('superadmin::sellers.view', array_merge(compact(
            'seller',
            'sellerForVue',
            'welcomeSubjectDefault',
            'welcomeMessageDefault',
            'sellerApplication',
            'applicationCountryName'
        ), [
            'sellerOrdersDatagridPayload' => $this->datagridFormattedWithNestedPagination(SellerViewOrderDataGrid::class, 'seller_orders'),
            'sellerInvoicesDatagridPayload' => $this->datagridFormattedWithNestedPagination(SellerViewInvoiceDataGrid::class, 'seller_invoices'),
            'sellerReviewsDatagridPayload' => $this->datagridFormattedWithNestedPagination(SellerViewReviewDataGrid::class, 'seller_reviews'),
        ]));
    }

    public function ordersData(int $id): BinaryFileResponse|JsonResponse
    {
        request()->merge([
            'seller_id' => $id,
        ]);

        return datagrid(SellerViewOrderDataGrid::class)->process();
    }

    /**
     * Send a welcome email to the seller (subject + plain text or HTML body inside the branded Blade layout).
     */
    public function sendWelcomeEmail(Request $request, int $id): RedirectResponse
    {
        if (! bouncer()->hasPermission('sellers.all.edit') && ! bouncer()->hasPermission('marketing.email_management')) {
            abort(403);
        }

        $seller = $this->adminRepository->findOrFail($id);

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:500000',
        ]);

        $messageBody = $this->renderWelcomeMessageBody($data['message']);

        $bodyHtml = view('superadmin::emails.seller-welcome', [
            'sellerName' => $seller->name,
            'loginUrl' => route('admin.session.create'),
            'messageBody' => $messageBody,
            'emailTitle' => $data['subject'],
        ])->render();

        try {
            app(SuperAdminEmailService::class)->send(
                $seller->email,
                $data['subject'],
                $bodyHtml,
                'seller',
                (int) $seller->id,
                'welcome'
            );
        } catch (\Throwable $e) {
            return redirect()
                ->route('superadmin.sellers.view', $id)
                ->with('error', trans('superadmin::app.sellers.view.welcome-email-failed', ['message' => $e->getMessage()]));
        }

        return redirect()
            ->route('superadmin.sellers.view', $id)
            ->with('success', trans('superadmin::app.sellers.view.welcome-email-sent'));
    }

    public function storeNote(int $id): RedirectResponse
    {
        request()->validate([
            'note' => 'required|string',
        ]);

        Admin::query()->findOrFail($id);

        SellerNote::query()->create([
            'seller_id' => $id,
            'note' => request()->input('note'),
            'seller_notified' => request()->boolean('customer_notified'),
        ]);

        session()->flash('success', trans('superadmin::app.customers.customers.view.note-created-success'));

        return redirect()->route('superadmin.sellers.view', $id);
    }

    public function updateProfile(Request $request, int $id): JsonResponse
    {
        $seller = Admin::query()->findOrFail($id);

        if ($request->input('max_visible_products') === '' || $request->input('max_visible_products') === null) {
            $request->merge(['max_visible_products' => 0]);
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:seller,email,'.$id,
            'wallet_balance' => 'nullable|numeric|gte:0',
            'credit_score' => 'nullable|integer|min:0|max:100',
            'max_visible_products' => 'required|integer|min:0',
        ];

        if (Schema::hasColumn('seller', 'referral_code')) {
            $rules['referral_code'] = [
                'required',
                'string',
                'max:32',
                Rule::unique('seller', 'referral_code')->ignore($id),
            ];
        }

        if (Schema::hasColumn('seller', 'seller_level')) {
            $rules['seller_level'] = ['required', 'string', Rule::in(SellerCommissionPercentRules::LEVELS)];
        }

        $data = $request->validate($rules);

        $seller->name = trim($data['first_name'].' '.$data['last_name']);
        $seller->email = $data['email'];
        $seller->status = $request->boolean('status', true) ? 1 : 0;

        if (Schema::hasColumn('seller', 'referral_code')) {
            $seller->referral_code = strtoupper(trim($data['referral_code']));
        }

        if ($request->has('wallet_balance')) {
            $seller->wallet_balance = $data['wallet_balance'];
        }

        if ($request->has('credit_score')) {
            $seller->credit_score = $data['credit_score'];
        }

        $seller->allowed_product_ids = null;
        $seller->max_visible_products = (int) $data['max_visible_products'];

        if (Schema::hasColumn('seller', 'seller_level')) {
            $seller->seller_level = $data['seller_level'];
        }

        Event::dispatch('user.admin.update.before', $id);

        $seller->save();

        $seller->load('role');

        Event::dispatch('user.admin.update.after', $seller);

        $fresh = $seller->fresh(['role']);

        return new JsonResponse([
            'message' => trans('superadmin::app.customers.customers.update-success'),
            'data' => [
                'customer' => $this->buildSellerVuePayload($fresh),
                'group' => ['name' => $fresh->role?->name],
            ],
        ]);
    }

    public function destroy(int $id): RedirectResponse
    {
        if (DB::table('orders')->where('seller_id', $id)->exists()) {
            session()->flash('error', trans('superadmin::app.sellers.index.datagrid.delete-has-orders'));

            return redirect()->route('superadmin.sellers.view', $id);
        }

        Event::dispatch('user.admin.delete.before', $id);

        $this->adminRepository->delete($id);

        Event::dispatch('user.admin.delete.after', $id);

        session()->flash('success', trans('superadmin::app.customers.customers.index.datagrid.delete-success'));

        return redirect()->route('superadmin.sellers.index');
    }

    public function edit(int $id): View
    {
        $seller = Admin::query()->with('role')->findOrFail($id);

        $sellerApplication = null;
        $applicationCountryName = null;

        if (Schema::hasTable('seller_applications')) {
            $sellerApplication = DB::table('seller_applications')
                ->where('seller_id', $seller->id)
                ->orderByDesc('id')
                ->first();

            if ($sellerApplication && ! empty($sellerApplication->country)) {
                $match = collect(core()->countries())->firstWhere('code', $sellerApplication->country);
                $applicationCountryName = $match ? $match->name : $sellerApplication->country;
            }
        }

        return view('superadmin::sellers.edit', compact('seller', 'sellerApplication', 'applicationCountryName'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $seller = Admin::query()->findOrFail($id);

        if ($request->input('max_visible_products') === '' || $request->input('max_visible_products') === null) {
            $request->merge(['max_visible_products' => 0]);
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:seller,email,'.$id,
            'status' => 'nullable|boolean',
            'wallet_balance' => 'required|numeric|gte:0',
            'credit_score' => 'required|integer|gte:0|lte:100',
            'max_visible_products' => 'required|integer|min:0',
            'shop_logo' => 'nullable|image|max:4096',
            'document_front' => 'nullable|image|max:4096',
            'document_back' => 'nullable|image|max:4096',
            'document_selfie' => 'nullable|image|max:4096',
            'shop_name' => 'nullable|string|max:255',
            'shop_address' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:120',
            'id_passport_number' => 'nullable|string|max:120',
            'legal_name' => 'nullable|string|max:255',
            'verify_type' => 'nullable|in:email,mobile',
            'application_email' => 'nullable|email|max:255',
            'mobile' => 'nullable|string|max:50',
            'invite_code' => 'nullable|string|max:120',
            'application_status' => 'nullable|in:pending,approved,rejected',
        ];

        if (Schema::hasColumn('seller', 'referral_code')) {
            $rules['referral_code'] = [
                'required',
                'string',
                'max:32',
                Rule::unique('seller', 'referral_code')->ignore($id),
            ];
        }

        if (Schema::hasColumn('seller', 'seller_level')) {
            $rules['seller_level'] = ['required', 'string', Rule::in(SellerCommissionPercentRules::LEVELS)];
        }

        $data = $request->validate($rules);

        $seller->name = trim($data['first_name'].' '.$data['last_name']);
        $seller->email = $data['email'];
        $seller->status = $request->boolean('status', true) ? 1 : 0;
        $seller->wallet_balance = $data['wallet_balance'];
        $seller->credit_score = $data['credit_score'];

        $seller->allowed_product_ids = null;
        $seller->max_visible_products = (int) $data['max_visible_products'];

        if (Schema::hasColumn('seller', 'referral_code')) {
            $seller->referral_code = strtoupper(trim((string) ($data['referral_code'] ?? '')));
        }

        if (Schema::hasColumn('seller', 'seller_level')) {
            $seller->seller_level = SellerCommissionPercentRules::normalizeLevel($data['seller_level'] ?? null);
        }

        $seller->save();

        if (Schema::hasTable('seller_applications')) {
            $application = DB::table('seller_applications')
                ->where('seller_id', $seller->id)
                ->orderByDesc('id')
                ->first();

            if ($application) {
                $baseDir = 'seller-applications/'.$seller->id;
                $docUpdates = [];
                $appFieldUpdates = [
                    'shop_name' => $request->input('shop_name'),
                    'shop_address' => $request->input('shop_address'),
                    'country' => $request->input('country'),
                    'id_passport_number' => $request->input('id_passport_number'),
                    'legal_name' => $request->input('legal_name'),
                    'verify_type' => $request->input('verify_type'),
                    'email' => $request->input('application_email'),
                    'mobile' => $request->input('mobile'),
                    'invite_code' => $request->input('invite_code'),
                    'status' => $request->input('application_status'),
                ];

                foreach (['shop_logo', 'document_front', 'document_back', 'document_selfie'] as $fileKey) {
                    if ($request->hasFile($fileKey)) {
                        $oldPath = (string) ($application->{$fileKey} ?? '');

                        if ($oldPath !== '') {
                            Storage::disk('public')->delete($oldPath);
                        }

                        $docUpdates[$fileKey] = $request->file($fileKey)->store($baseDir, 'public');
                    }
                }

                $updates = array_merge($appFieldUpdates, $docUpdates);

                if (! empty($updates)) {
                    $updates['updated_at'] = now();

                    DB::table('seller_applications')
                        ->where('id', $application->id)
                        ->update($updates);
                }
            }
        }

        return redirect()->route('superadmin.sellers.view', $seller->id)
            ->with('success', trans('superadmin::app.sellers.edit.success'));
    }

    /**
     * Redirect to storefront with an encrypted preview token (no raw seller id in the shop URL).
     */
    public function visitStoreFront(int $id): RedirectResponse
    {
        $this->adminRepository->findOrFail($id);

        $ttlSeconds = max(300, (int) config('seller_preview.token_ttl_seconds', 7200));

        $payload = json_encode([
            'sid' => $id,
            'exp' => now()->addSeconds($ttlSeconds)->timestamp,
        ], JSON_THROW_ON_ERROR);

        $token = Crypt::encryptString($payload);

        $channel = core()->getCurrentChannel();
        $base = rtrim((string) config('app.url'), '/');
        $host = trim((string) $channel->hostname);

        if ($host !== '' && preg_match('#^https?://#i', $host)) {
            $candidate = rtrim($host, '/');
            $hostname = strtolower((string) parse_url($candidate, PHP_URL_HOST));

            // Channel DB often has http://localhost (Apache :80) while the app runs on APP_URL
            // (e.g. php artisan serve :8000). Using loopback channel URLs here breaks redirects.
            $isLoopback = in_array($hostname, ['localhost', '127.0.0.1', '::1'], true);

            if (! $isLoopback) {
                $base = $candidate;
            }
        }

        URL::forceRootUrl($base);

        return redirect()->route('shop.tik-store.index', ['spv' => $token]);
    }

    public function loginAsSeller(int $id): RedirectResponse
    {
        $seller = $this->adminRepository->findOrFail($id);

        auth()->guard('admin')->login($seller);

        session()->put('superadmin_impersonate_seller', true);

        session()->flash('success', trans('superadmin::app.sellers.index.login-message', ['seller_name' => $seller->name]));

        return redirect()->route('admin.dashboard.index');
    }

    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $ids = $massUpdateRequest->input('indices');

        foreach ($ids as $sellerId) {
            Event::dispatch('user.admin.update.before', $sellerId);

            $seller = $this->adminRepository->update([
                'status' => $massUpdateRequest->input('value'),
            ], $sellerId);

            Event::dispatch('user.admin.update.after', $seller);
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.customers.customers.index.datagrid.update-success'),
        ]);
    }

    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $ids = $massDestroyRequest->input('indices');

        try {
            foreach ($ids as $sellerId) {
                if (DB::table('orders')->where('seller_id', $sellerId)->exists()) {
                    throw new \Exception(trans('superadmin::app.sellers.index.datagrid.delete-has-orders'));
                }
            }

            foreach ($ids as $sellerId) {
                Event::dispatch('user.admin.delete.before', $sellerId);

                $this->adminRepository->delete($sellerId);

                Event::dispatch('user.admin.delete.after', $sellerId);
            }

            return new JsonResponse([
                'message' => trans('superadmin::app.customers.customers.index.datagrid.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Plain text → escaped + line breaks; content that looks like HTML is output as-is (trusted Super Admin).
     */
    protected function renderWelcomeMessageBody(string $message): string
    {
        $trimmed = trim($message);
        if ($trimmed === '') {
            return '';
        }

        if (preg_match('/<[a-z][a-z0-9]*\b/i', $trimmed)) {
            return $message;
        }

        return nl2br(e($message), false);
    }
}
