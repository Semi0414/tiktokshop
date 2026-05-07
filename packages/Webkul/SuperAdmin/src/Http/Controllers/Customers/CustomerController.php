<?php

namespace Webkul\SuperAdmin\Http\Controllers\Customers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Customer\Models\CustomerWalletTransaction;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerNoteRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\SuperAdmin\DataGrids\Customers\CustomerDataGrid;
use Webkul\SuperAdmin\DataGrids\Customers\View\InvoiceDataGrid;
use Webkul\SuperAdmin\DataGrids\Customers\View\OrderDataGrid;
use Webkul\SuperAdmin\DataGrids\Customers\View\ReviewDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Requests\MassDestroyRequest;
use Webkul\SuperAdmin\Http\Requests\MassUpdateRequest;
use Webkul\SuperAdmin\Mail\Customer\NewCustomerNotification;

class CustomerController extends Controller
{
    /**
     * Ajax request for orders.
     */
    public const ORDERS = 'orders';

    /**
     * Ajax request for invoices.
     */
    public const INVOICES = 'invoices';

    /**
     * Ajax request for reviews.
     */
    public const REVIEWS = 'reviews';

    /**
     * Static pagination count.
     *
     * @var int
     */
    public const COUNT = 10;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected CustomerNoteRepository $customerNoteRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        $channels = core()->getAllChannels();

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return $this->superadminListing('superadmin::customers.customers.index', compact('channels', 'groups'), CustomerDataGrid::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'gender' => 'required',
            'channel_id' => 'required|integer',
            'email' => 'required|unique:customers,email,NULL,id,channel_id,'.request('channel_id'),
            'date_of_birth' => 'date|before:today',
            'phone' => ['unique:customers,phone', new PhoneNumber],
        ]);

        $password = rand(100000, 10000000);

        Event::dispatch('customer.registration.before');

        $data = array_merge([
            'password' => bcrypt($password),
            'is_verified' => 1,
        ], request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'customer_group_id',
            'channel_id',
        ]));

        if (empty($data['phone'])) {
            $data['phone'] = null;
        }

        Event::dispatch('customer.create.before');

        $customer = $this->customerRepository->create($data);

        if (core()->getConfigData('emails.general.notifications.emails.general.notifications.customer_account_credentials')) {
            try {
                Mail::queue(new NewCustomerNotification($customer, $password));
            } catch (\Exception $e) {
                report($e);
            }
        }

        Event::dispatch('customer.create.after', $customer);

        Event::dispatch('customer.registration.after', $customer);

        return new JsonResponse([
            'data' => $customer,
            'message' => trans('superadmin::app.customers.customers.index.create.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function update(int $id)
    {
        $rules = [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'gender' => 'required',
            'email' => 'required|unique:customers,email,'.$id,
            'date_of_birth' => 'date|before:today',
            'phone' => ['unique:customers,phone,'.$id, new PhoneNumber],
            'wallet_balance' => 'nullable|numeric',
            'credit_score' => 'nullable|integer|min:0|max:100',
        ];

        if (Schema::hasColumn('customers', 'referral_seller_id')) {
            $rules['referral_code'] = 'nullable|string|max:32';
        }

        $this->validate(request(), $rules);

        $data = request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'customer_group_id',
            'status',
            'is_suspended',
            'wallet_balance',
            'credit_score',
        ]);

        if (empty($data['phone'])) {
            $data['phone'] = null;
        }

        if (Schema::hasColumn('customers', 'referral_seller_id') && request()->has('referral_code')) {
            $code = trim((string) request()->input('referral_code'));
            if ($code === '') {
                $data['referral_seller_id'] = null;
            } else {
                $code = strtoupper($code);
                $sellerId = DB::table('seller')->where('referral_code', $code)->value('id');
                if (! $sellerId) {
                    return new JsonResponse([
                        'message' => trans('superadmin::app.customers.customers.view.edit.referral-invalid'),
                        'errors' => ['referral_code' => [trans('superadmin::app.customers.customers.view.edit.referral-invalid')]],
                    ], 422);
                }
                $data['referral_seller_id'] = (int) $sellerId;
            }
        }

        Event::dispatch('customer.update.before', $id);

        $customer = $this->customerRepository->update($data, $id);

        Event::dispatch('customer.update.after', $customer);

        $fresh = $customer->fresh(['group', 'referralSeller']);
        if (Schema::hasColumn('customers', 'referral_seller_id')) {
            $fresh->setAttribute('seller_referral_code', $fresh->referralSeller?->referral_code);
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.customers.customers.update-success'),
            'data' => [
                'customer' => $fresh,
                'group' => $fresh->group,
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(int $id)
    {
        $customer = $this->customerRepository->findorFail($id);

        if (! $customer) {
            return response()->json(['message' => trans('superadmin::app.customers.customers.delete-failed')], 400);
        }

        if (! $this->customerRepository->haveActiveOrders($customer)) {

            $this->customerRepository->delete($id);

            session()->flash('success', trans('superadmin::app.customers.customers.delete-success'));

            return redirect()->route('superadmin.customers.customers.index');
        }

        session()->flash('error', trans('superadmin::app.customers.customers.view.order-pending'));

        return redirect()->route('superadmin.customers.customers.index');
    }

    /**
     * Login as customer.
     *
     * @return RedirectResponse
     */
    public function loginAsCustomer(int $id)
    {
        $customer = $this->customerRepository->findOrFail($id);

        auth()->guard('customer')->login($customer);

        session()->flash('success', trans('superadmin::app.customers.customers.index.login-message', ['customer_name' => $customer->name]));

        return redirect(route('shop.customers.account.profile.index'));
    }

    /**
     * To store the response of the note.
     *
     * @return RedirectResponse
     */
    public function storeNotes(int $id)
    {
        $this->validate(request(), [
            'note' => 'string|required',
        ]);

        Event::dispatch('customer.note.create.before', $id);

        $customerNote = $this->customerNoteRepository->create([
            'customer_id' => $id,
            'note' => request()->input('note'),
            'customer_notified' => request()->input('customer_notified', 0),
        ]);

        Event::dispatch('customer.note.create.after', $customerNote);

        session()->flash('success', trans('superadmin::app.customers.customers.view.note-created-success'));

        return redirect()->route('superadmin.customers.customers.view', $id);
    }

    /**
     * Update wallet balance and credit score for a customer.
     */
    public function updateWallet(int $id): JsonResponse
    {
        $this->validate(request(), [
            'amount' => 'nullable|numeric',
            'credit_score' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        $customer = $this->customerRepository->findOrFail($id);

        $amount = (float) request()->input('amount', 0);
        $creditScore = request()->input('credit_score');

        DB::beginTransaction();

        try {
            $balanceBefore = $customer->wallet_balance ?? 0.0;
            $balanceAfter = $balanceBefore;

            if ($amount !== 0.0) {
                $balanceAfter = max(0, $balanceBefore + $amount);

                CustomerWalletTransaction::create([
                    'customer_id' => $customer->id,
                    'amount' => abs($amount),
                    'type' => $amount >= 0 ? 'credit' : 'debit',
                    'description' => request()->input('description'),
                    'order_id' => null,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                ]);

                $customer->wallet_balance = $balanceAfter;
            }

            if (! is_null($creditScore)) {
                $customer->credit_score = (int) $creditScore;
            }

            $customer->save();

            DB::commit();

            return new JsonResponse([
                'message' => trans('superadmin::app.customers.customers.update-success'),
                'data' => [
                    'wallet_balance' => $customer->wallet_balance,
                    'credit_score' => $customer->credit_score,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * View all details of customer.
     */
    public function show(int $id): View|BinaryFileResponse|JsonResponse
    {
        if (request()->boolean('export')) {
            return match (request()->query('dg')) {
                'orders' => $this->resolveNestedPaginationExport(OrderDataGrid::class, 'customer_orders'),
                'invoices' => $this->resolveNestedPaginationExport(InvoiceDataGrid::class, 'customer_invoices'),
                'reviews' => $this->resolveNestedPaginationExport(ReviewDataGrid::class, 'customer_reviews'),
                default => abort(404),
            };
        }

        $customer = $this->customerRepository->with(['addresses', 'group', 'referralSeller'])->findOrFail($id);

        if (Schema::hasColumn('customers', 'referral_seller_id')) {
            $customer->setAttribute(
                'seller_referral_code',
                $customer->referralSeller?->referral_code
            );
        }

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('superadmin::customers.customers.view', array_merge(compact('customer', 'groups'), [
            'customerOrdersDatagridPayload' => $this->datagridFormattedWithNestedPagination(OrderDataGrid::class, 'customer_orders'),
            'customerInvoicesDatagridPayload' => $this->datagridFormattedWithNestedPagination(InvoiceDataGrid::class, 'customer_invoices'),
            'customerReviewsDatagridPayload' => $this->datagridFormattedWithNestedPagination(ReviewDataGrid::class, 'customer_reviews'),
        ]));
    }

    /**
     * Result of search customer.
     *
     * @return JsonResponse
     */
    public function search()
    {
        $customers = $this->customerRepository->scopeQuery(function ($query) {
            return $query->where('email', 'like', '%'.urldecode(request()->input('query')).'%')
                ->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', '%'.urldecode(request()->input('query')).'%')
                ->orderBy('created_at', 'desc');
        })->paginate(self::COUNT);

        return response()->json($customers);
    }

    /**
     * To mass update the customer.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $selectedCustomerIds = $massUpdateRequest->input('indices');

        foreach ($selectedCustomerIds as $customerId) {
            Event::dispatch('customer.update.before', $customerId);

            $customer = $this->customerRepository->update([
                'status' => $massUpdateRequest->input('value'),
            ], $customerId);

            Event::dispatch('customer.update.after', $customer);
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.customers.customers.index.datagrid.update-success'),
        ]);
    }

    /**
     * To mass delete the customer.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $customers = $this->customerRepository->findWhereIn('id', $massDestroyRequest->input('indices'));

        try {
            /**
             * Ensure that customers do not have any active orders before performing deletion.
             */
            foreach ($customers as $customer) {
                if ($this->customerRepository->haveActiveOrders($customer)) {
                    throw new \Exception(trans('superadmin::app.customers.customers.index.datagrid.order-pending'));
                }
            }

            /**
             * After ensuring that they have no active orders delete the corresponding customer.
             */
            foreach ($customers as $customer) {
                Event::dispatch('customer.delete.before', $customer);

                $this->customerRepository->delete($customer->id);

                Event::dispatch('customer.delete.after', $customer);
            }

            return new JsonResponse([
                'message' => trans('superadmin::app.customers.customers.index.datagrid.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
