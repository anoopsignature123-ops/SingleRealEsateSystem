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
        $reports = $this->buildQuery($request)->latest()->get();

        $summary = [
            'total_records' => $reports->count(),
            'cleared_records' => $reports->where('cheque_status', 'cleared')->count(),
            'bounced_records' => $reports->where('cheque_status', 'bounced')->count(),
            'total_amount' => $reports->sum(fn($item) => (float) ($item->paid_amount ?? $item->booking_amount ?? 0)),
        ];

        return view('reports.cheque-details-report.index', compact('reports', 'summary'));
    }

    public function export(Request $request)
    {
        $reports = $this->buildQuery($request)->latest()->get();

        return $this->excelExportService->export(
            $reports,
            'cheque-details-report',
            [
                'Customer ID',
                'Customer Name',
                'Booking ID',
                'Payment Type',
                'Pay Mode',
                'Amount',
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
                    $report->customerBooking?->booking_code ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $report->plan_type ?? 'N/A')),
                    strtoupper($report->payment_mode ?? 'N/A'),
                    number_format($report->paid_amount ?? $report->booking_amount ?? 0, 2, '.', ''),
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

    private function buildQuery(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
        ])
            ->where('payment_mode', 'cheque')
            ->whereIn('cheque_status', ['cleared', 'bounced']);

        if ($request->filled('criteria')) {
            $query->where('plan_type', $request->criteria);
        }

        if ($request->filled('cheque_status')) {
            $query->where('cheque_status', $request->cheque_status);
        }

        return $query;
    }
}