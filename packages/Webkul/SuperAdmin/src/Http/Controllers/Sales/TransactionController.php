<?php

namespace Webkul\SuperAdmin\Http\Controllers\Sales;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Payment\Facades\Payment;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Repositories\ShipmentRepository;
use Webkul\SuperAdmin\DataGrids\Sales\OrderTransactionDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected ShipmentRepository $shipmentRepository,
        protected OrderTransactionRepository $orderTransactionRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        $paymentMethods = Payment::getSupportedPaymentMethods();

        return $this->superadminListing('superadmin::sales.transactions.index', compact('paymentMethods'), OrderTransactionDataGrid::class);
    }

    /**
     * Save the transaction.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $this->validate(request(), [
            'invoice_id' => 'required',
            'payment_method' => 'required',
            'amount' => 'required|numeric',
        ]);

        $invoice = $this->invoiceRepository->where('id', $request->invoice_id)->first();

        if (! $invoice) {
            return $this->transactionErrorResponse(
                $request,
                trans('superadmin::app.sales.transactions.index.create.invoice-missing')
            );
        }

        $transactionAmtBefore = $this->orderTransactionRepository->where('invoice_id', $invoice->id)->sum('amount');

        $transactionAmtFinal = $request->amount + $transactionAmtBefore;

        if ($invoice->state == 'paid') {
            return $this->transactionErrorResponse(
                $request,
                trans('superadmin::app.sales.transactions.index.create.already-paid')
            );
        }

        if ($transactionAmtFinal > $invoice->base_grand_total) {
            return $this->transactionErrorResponse(
                $request,
                trans('superadmin::app.sales.transactions.index.create.transaction-amount-exceeds')
            );
        }

        if ($request->amount <= 0) {
            return $this->transactionErrorResponse(
                $request,
                trans('superadmin::app.sales.transactions.index.create.transaction-amount-zero')
            );
        }

        $order = $this->orderRepository->find($invoice->order_id);

        $this->orderTransactionRepository->create([
            'transaction_id' => bin2hex(random_bytes(20)),
            'type' => $request->payment_method,
            'payment_method' => $request->payment_method,
            'invoice_id' => $invoice->id,
            'order_id' => $invoice->order_id,
            'amount' => $request->amount,
            'status' => 'paid',
            'data' => json_encode([
                'paidAmount' => $request->amount,
            ]),
        ]);

        $transactionTotal = $this->orderTransactionRepository->where('invoice_id', $invoice->id)->sum('amount');

        if ($transactionTotal >= $invoice->base_grand_total) {
            $shipments = $this->shipmentRepository->where('order_id', $invoice->order_id)->first();

            $status = isset($shipments)
                ? Order::STATUS_COMPLETED
                : Order::STATUS_PROCESSING;

            $this->orderRepository->updateOrderStatus($order, $status);

            $this->invoiceRepository->updateState($invoice, Invoice::STATUS_PAID);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return new JsonResponse([
                'message' => trans('superadmin::app.sales.transactions.index.create.transaction-saved'),
            ]);
        }

        session()->flash('success', trans('superadmin::app.sales.transactions.index.create.transaction-saved'));
        
        return redirect()->route('superadmin.sales.transactions.index');
    }

    protected function transactionErrorResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return new JsonResponse([
                'message' => $message,
            ], 400);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $message);
    }

    /**
     * Show the view for the specified resource.
     */
    public function view(int $id): TransactionResource
    {
        $transaction = $this->orderTransactionRepository->findOrFail($id);

        return new TransactionResource($transaction);
    }
}
