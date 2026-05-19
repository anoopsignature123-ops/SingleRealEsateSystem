<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class ChequeDetailsReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $query = CustomerPayment::with(['customerBooking.primaryDetail'])->where('payment_mode', 'cheque')
            ->where('cheque_status', 'cleared');
        if ($request->filled('criteria')) {
            $query->where('plan_type', $request->criteria);
        }
        $reports = $query->latest()->get();

        return view('reports.cheque-details-report.index', compact('reports'));
    }

    public function export(Request $request)
    {
        $query = CustomerPayment::with(['customerBooking.primaryDetail'])->where('payment_mode', 'cheque')
            ->where('cheque_status', 'cleared');
        if ($request->filled('criteria')) {
            $query->where('plan_type', $request->criteria);
        }
        $reports = $query->latest()->get();

        return $this->excelExportService->export($reports, 'cheque-details-report',
            [
                'Customer ID',
                'Customer Name',
                'Payment Type',
                'Pay Mode',
                'Bank Account No',
                'Cheque No',
                'Bank Name',
                'Bank Branch',
                'Pay Date',
                'Cheque Status',
            ],
            function ($report) {
                return [
                    $report->customerBooking?->customer_code ?? 'N/A',
                    $report->customerBooking?->primaryDetail?->name ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $report->plan_type)),
                    strtoupper($report->payment_mode ?? 'N/A'),
                    $report->account_number ?? 'N/A',
                    $report->cheque_number ?? 'N/A',
                    $report->bank_name ?? 'N/A',
                    $report->branch_name ?? 'N/A',
                    $report->cheque_date ? date('d-m-Y', strtotime($report->cheque_date)) : 'N/A',
                    strtoupper($report->cheque_status ?? 'N/A'),
                ];
            }
        );
    }
}
