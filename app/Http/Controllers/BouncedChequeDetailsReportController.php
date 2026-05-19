<?php

namespace App\Http\Controllers;

use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class BouncedChequeDetailsReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $customers = CustomerBooking::select('id', 'customer_code')->get();
        $reports = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'customerBooking.associate',
            'plotSaleDetail.plotDetail',
        ])->where('cheque_status', 'bounced');
        if ($request->filled('customer_id')) {
            $reports->where('customer_booking_id', $request->customer_id);
        }
        if ($request->filled('from_date')) {
            $reports->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $reports->whereDate('created_at', '<=', $request->to_date);
        }
        $reports = $reports->latest()->get();

        return view('reports.bounced-cheque-details-report.index', compact('customers', 'reports'));
    }

    public function export(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'customerBooking.associate',
            'plotSaleDetail.plotDetail',
        ])->where('cheque_status', 'bounced');
        if ($request->filled('customer_id')) {
            $query->where('customer_booking_id', $request->customer_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        $reports = $query->latest()->get();

        return $this->excelExportService->export($reports, 'bounced-cheque-details-report',
            [
                'Customer Id',
                'Customer Name',
                'Booking Id',
                'Agent Id',
                'Plot No',
                'Cheque No',
                'Cheque Date',
                'Bank Name',
                'Branch Name',
                'Cheque Reason',
                'Paid Amount',
                'Date',
            ],
            function ($report) {
                return [
                    $report->customerBooking?->customer_code ?? 'N/A',
                    $report->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $report->customerBooking?->booking_code ?? 'N/A',
                    $report->customerBooking?->associate?->associate_id ?? 'N/A',
                    $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    $report->cheque_number ?? 'N/A',
                    $report->cheque_date ? $report->cheque_date->format('d-m-Y') : 'N/A',
                    $report->bank_name ?? 'N/A',
                    $report->branch_name ?? 'N/A',
                    $report->cheque_reason ?? 'N/A',
                    $report->booking_amount ?? 0,
                    $report->created_at?->format('d-m-Y'),
                ];
            }
        );
    }
}
