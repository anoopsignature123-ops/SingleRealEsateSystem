<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class OneTimePaymentDueController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.plotDetail',
        ])
            ->where('plan_type', 'full_payment');

        // Customer ID Filter
        if ($request->filled('customer_id')) {
            $query->whereHas('customerBooking', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
        }

        // Customer Name Filter
        if ($request->filled('customer_name')) {
            $query->whereHas('customerBooking.primaryDetail', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->customer_name.'%');
            });
        }

        $payments = $query->get();

        // Dropdown Data
        $customerIds = CustomerPayment::where('plan_type', 'full_payment')
            ->with('customerBooking')
            ->get();

        return view(
            'reports.one-time-payment-due.index',
            compact('payments', 'customerIds')
        );
    }

    public function export(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.plotDetail',
        ])->where('plan_type', 'full_payment');

        // Customer ID Filter
        if ($request->customer_id) {
            $query->whereHas('customerBooking', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
        }

        // Customer Name Filter
        if ($request->customer_name) {
            $query->whereHas('customerBooking.primaryDetail', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->customer_name.'%');
            });
        }

        $payments = $query->latest()->get();

        return $this->excelExportService->export(
            $payments,
            'one-time-payment-due-report',
            [
                'Booking ID',
                'Customer Name',
                'Project Name',
                'Plot No',
                'Payable Amount',
                'Paid Amount',
                'Due Amount',
            ],
            function ($payment) {

                $paid = $payment->booking_amount ?? 0;
                $payable = $payment->net_payable_amount ?? 0;
                $due = $payable - $paid;

                return [
                    $payment->customerBooking?->booking_code ?? 'N/A',
                    $payment->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $payment->plotSaleDetail?->project?->name ?? 'N/A',
                    $payment->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    $payable,
                    $paid,
                    $due,
                ];
            }
        );
    }
}
