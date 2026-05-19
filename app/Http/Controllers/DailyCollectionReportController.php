<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyCollectionReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $reports = collect();
        if ($request->has('search')) {
            $query = CustomerPayment::with([
                'customerBooking.primaryDetail',
                'customerBooking.associate',
                'plotSaleDetail.plotDetail',
            ]);
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }
            $reports = $query->latest()->get();
        }

        return view('reports.daily-collection-report.index', compact('reports'));
    }

    public function export(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'customerBooking.associate',
            'plotSaleDetail.plotDetail',
        ]);
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        $reports = $query->latest()->get();

        return $this->excelExportService->export($reports, 'daily-collection-report',
            [
                'Agent Id',
                'Customer Id',
                'Customer Name',
                'Booking Id',
                'Plot No',
                'Plan Type',
                'Payment Type',
                'Receipt No',
                'Total Cost',
                'Paymode / Cheque / DD / Reference No',
                'Paid Amt.',
                'Date',
            ],
            function ($report) {
                $paymentRef = '-';
                if ($report->payment_mode == 'Cheque') {
                    $paymentRef = $report->cheque_number;
                } elseif ($report->payment_mode == 'DD') {
                    $paymentRef = $report->dd_number;
                } else {
                    $paymentRef = $report->transaction_number;
                }

                return [
                    $report->customerBooking?->associate?->associate_id ?? 'N/A',
                    $report->customerBooking?->customer_code ?? 'N/A',
                    $report->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $report->customerBooking?->booking_code ?? 'N/A',
                    $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    ucfirst($report->plan_type ?? 'N/A'),
                    ucfirst($report->payment_mode ?? 'N/A'),
                    $report->receipt_number ?? 'N/A',
                    $report->net_payable_amount ?? 0,
                    $paymentRef,
                    $report->booking_amount ?? 0,
                    $report->created_at ? Carbon::parse($report->created_at)->format('d-m-Y') : 'N/A',
                ];
            }
        );
    }
}
