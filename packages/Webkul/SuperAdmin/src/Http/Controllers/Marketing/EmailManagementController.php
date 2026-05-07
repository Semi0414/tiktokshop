<?php

namespace Webkul\SuperAdmin\Http\Controllers\Marketing;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webkul\Customer\Models\Customer;
use Webkul\SuperAdmin\DataGrids\Marketing\EmailManagementDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Models\SuperAdminEmailLog;
use Webkul\SuperAdmin\Services\SuperAdminEmailService;
use Webkul\User\Models\Admin;

class EmailManagementController extends Controller
{
    private const COMPOSE_SELECT_LIMIT = 500;

    public function __construct(
        protected SuperAdminEmailService $emailService
    ) {}

    public function index(Request $request): ViewContract|BinaryFileResponse|JsonResponse
    {
        $logsReady = Schema::hasTable('superadmin_email_logs');

        abort_unless(bouncer()->hasPermission('marketing.email_management'), 403);

        $composeDefaults = null;

        if ($logsReady && request()->boolean('export')) {
            return datagrid(EmailManagementDataGrid::class)->process();
        }

        if ($logsReady && $request->filled('compose_from')) {
            $log = SuperAdminEmailLog::query()->find((int) $request->query('compose_from'));
            if ($log) {
                $composeDefaults = [
                    'recipient_type' => $log->recipient_type,
                    'recipient_id' => $log->recipient_id,
                    'to_email' => $log->to_email,
                    'subject' => $log->subject,
                    'body_html' => $log->body_html ?? '',
                ];

                if ($log->recipient_type === 'seller' && $log->recipient_id) {
                    $seller = Admin::query()->find($log->recipient_id);
                    $composeDefaults['seller_label'] = $seller
                        ? $seller->name.' ('.$seller->email.')'
                        : null;
                }

                if ($log->recipient_type === 'customer' && $log->recipient_id) {
                    $customer = Customer::query()->find($log->recipient_id);
                    $composeDefaults['customer_label'] = $customer
                        ? $customer->name.' ('.$customer->email.')'
                        : null;
                }
            }
        }

        $sellerCount = Admin::query()->count();
        $customerCount = Customer::query()->count();

        $viewData = [
            'logsUnavailable' => ! $logsReady,
            'composeDefaults' => $composeDefaults,
            'composeSellerOptions' => Admin::query()->orderBy('id')->limit(self::COMPOSE_SELECT_LIMIT)->get(['id', 'name', 'email']),
            'composeCustomerOptions' => Customer::query()->orderBy('id')->limit(self::COMPOSE_SELECT_LIMIT)->get(['id', 'first_name', 'last_name', 'email']),
            'composeSellerListTruncated' => $sellerCount > self::COMPOSE_SELECT_LIMIT,
            'composeCustomerListTruncated' => $customerCount > self::COMPOSE_SELECT_LIMIT,
            'composeRecipientSelectLimit' => self::COMPOSE_SELECT_LIMIT,
        ];

        if ($logsReady) {
            $viewData['datagridPayload'] = datagrid_formatted(EmailManagementDataGrid::class);
        }

        return view('superadmin::email-management.index', $viewData);
    }

    public function destroy(int $id): RedirectResponse
    {
        abort_unless(bouncer()->hasPermission('marketing.email_management'), 403);

        $log = SuperAdminEmailLog::query()->findOrFail($id);
        $log->delete();

        return back()->with('success', trans('superadmin::app.email-management.log-deleted'));
    }

    public function send(): RedirectResponse
    {
        abort_unless(bouncer()->hasPermission('marketing.email_management'), 403);

        $data = request()->validate([
            'recipient_type' => 'required|in:custom,seller,customer',
            'to_email' => 'nullable|email|max:255',
            'seller_id' => 'nullable|integer|min:1',
            'customer_id' => 'nullable|integer|min:1',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string|max:500000',
        ]);

        $to = null;
        $recipientId = null;
        $recipientType = $data['recipient_type'];

        if ($recipientType === 'custom') {
            $to = $data['to_email'] ?? null;
            if (! $to) {
                return back()->withInput()->with('error', trans('superadmin::app.email-management.error-missing-email'));
            }
        } elseif ($recipientType === 'seller') {
            $seller = Admin::query()->find($data['seller_id'] ?? 0);
            if (! $seller) {
                return back()->withInput()->with('error', trans('superadmin::app.email-management.error-invalid-seller'));
            }
            $to = $seller->email;
            $recipientId = $seller->id;
        } else {
            $customer = Customer::query()->find($data['customer_id'] ?? 0);
            if (! $customer) {
                return back()->withInput()->with('error', trans('superadmin::app.email-management.error-invalid-customer'));
            }
            $to = $customer->email;
            $recipientId = $customer->id;
        }

        try {
            $this->emailService->send(
                $to,
                $data['subject'],
                $data['body_html'],
                $recipientType,
                $recipientId,
                'custom'
            );
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', trans('superadmin::app.email-management.send-failed', ['message' => $e->getMessage()]));
        }

        return back()->with('success', trans('superadmin::app.email-management.send-success-sync'));
    }
}
