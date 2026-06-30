<?php

namespace App\Http\Controllers;

use App\Http\Requests\MultipleChequeClearanceRequest;
use App\Models\CustomerPayment;
use App\Services\ChequeClearanceService;

class ChequeClearanceController extends Controller
{
    protected ChequeClearanceService $service;

    public function __construct(ChequeClearanceService $service)
    {
        $this->service = $service;
    }

    public function multipleChequeClearanceIndex()
    {
        $paymentRows = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ])
            ->whereIn('payment_mode', ['cheque', 'dd'])
            ->where(function ($query) {
                $query->whereNull('cheque_status')
                    ->orWhere('cheque_status', '!=', 'cleared');
            })
            ->latest()
            ->get();

        $payments = $paymentRows
            ->groupBy(fn ($payment) => $payment->receipt_number ?: 'payment-'.$payment->id)
            ->map(function ($receiptPayments) {
                $payment = $receiptPayments->first();
                $customer = $payment->customerBooking;
                $plotSales = $receiptPayments->pluck('plotSaleDetail')->filter();
                $statuses = $receiptPayments
                    ->map(fn ($item) => $item->cheque_status ?: 'pending')
                    ->unique()
                    ->values();
                $status = $statuses->count() === 1 ? $statuses->first() : 'mixed';

                return [
                    'payment_ids' => $receiptPayments->pluck('id')->implode(','),
                    'receipt_number' => $payment->receipt_number,
                    'created_at' => $payment->created_at,
                    'customer_name' => $customer?->primaryDetail?->name ?? ($customer?->customer_name ?? '-'),
                    'customer_code' => $customer?->customer_code ?? '-',
                    'projects' => $plotSales->map(fn ($sale) => $sale?->project?->name)->filter()->unique()->implode(', ') ?: '-',
                    'plots' => $plotSales->map(fn ($sale) => $sale?->plotDetail?->plot_number)->filter()->unique()->implode(', ') ?: '-',
                    'blocks' => $plotSales->map(fn ($sale) => $sale?->block?->block)->filter()->unique()->implode(', ') ?: '-',
                    'booking_codes' => $plotSales->map(fn ($sale) => $sale?->booking_code)->filter()->unique()->implode(', ') ?: '-',
                    'amount' => (float) $receiptPayments->sum(fn ($item) => $item->paid_amount ?? $item->booking_amount ?? 0),
                    'bank_name' => $payment->bank_name ?? '-',
                    'payment_mode' => $payment->payment_mode,
                    'reference' => $payment->payment_mode === 'dd'
                        ? 'DD: '.($payment->dd_number ?? '-')
                        : 'Cheque: '.($payment->cheque_number ?? '-'),
                    'cheque_date' => $payment->cheque_date,
                    'status' => $status,
                    'record_count' => $receiptPayments->count(),
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        $summary = [
            'total' => $payments->count(),
            'pending' => $payments->where('status', 'pending')->count(),
            'bounced' => $payments->where('status', 'bounced')->count(),
            'amount' => (float) $payments->sum('amount'),
        ];

        return view('payment.multiple-cheque-clearance.index', compact('payments', 'summary'));
    }

    public function storeMultipleChequeClearance(MultipleChequeClearanceRequest $request)
    {
        $this->service->store($request->validated());

        return back()->with('success', 'Cheque status updated successfully');
    }
}
