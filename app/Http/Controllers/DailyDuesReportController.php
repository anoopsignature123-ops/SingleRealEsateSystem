<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyDuesReportController extends Controller
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
                $query->whereDate('created_at', '>=', Carbon::parse($request->from_date));
            }
            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', Carbon::parse($request->to_date));
            }
            $reports = $query->latest()->get();
        }

        return view('reports.daily-dues-report.index', compact('reports'));
    }

    public function export(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'customerBooking.associate',
            'plotSaleDetail.plotDetail',
        ]);
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }
        $reports = $query->latest()->get();

        return $this->excelExportService->export($reports, 'daily-dues-report',
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
                'Paymode Cheque/DD/ReferenceNo',
                'Paid Amt',
                'Date',
            ],
            function ($report) {
                return [
                    $report->customerBooking?->associate?->associate_id ?? 'N/A',
                    $report->customerBooking?->customer_code ?? 'N/A',
                    $report->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $report->customerBooking?->booking_code ?? 'N/A',
                    $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $report->plan_type ?? 'N/A')),
                    ucfirst(str_replace('_', ' ', $report->payment_status ?? 'N/A')),
                    $report->receipt_number ?? $report->manual_receipt_number ?? 'N/A',
                    $report->net_payable_amount ?? 0,
                    $report->cheque_number ?? $report->dd_number ?? $report->transaction_number ?? 'N/A',
                    $report->booking_amount ?? 0,
                    $report->created_at ? Carbon::parse($report->created_at)->format('d-m-Y') : 'N/A',
                ];
            }
        );
    }
}
