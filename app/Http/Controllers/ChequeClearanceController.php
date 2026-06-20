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
        $payments = CustomerPayment::with([
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

        $summary = [
            'total' => $payments->count(),
            'pending' => $payments->where('cheque_status', 'pending')->count()
                + $payments->whereNull('cheque_status')->count(),
            'bounced' => $payments->where('cheque_status', 'bounced')->count(),
            'amount' => (float) $payments->sum('paid_amount'),
        ];

        return view('payment.multiple-cheque-clearance.index', compact('payments', 'summary'));
    }

    public function storeMultipleChequeClearance(MultipleChequeClearanceRequest $request)
    {
        $this->service->store($request->validated());

        return back()->with('success', 'Cheque status updated successfully');
    }
}
