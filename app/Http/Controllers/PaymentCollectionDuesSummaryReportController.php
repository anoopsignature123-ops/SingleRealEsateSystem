<?php

namespace App\Http\Controllers;

use App\Models\CustomerBooking;
use App\Services\ExcelExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentCollectionDuesSummaryReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $customerIds = CustomerBooking::select('id', 'customer_code')->get();
        $query = CustomerBooking::with([
            'primaryDetail',
            'plotSaleDetail.plotDetail',
            'payment',
            'payments',
        ]);
        if ($request->filled('date')) {
            $query->whereDate('created_at', Carbon::parse($request->date));
        }
        if ($request->filled('customer_id')) {
            $query->where('id', $request->customer_id);
        }
        $reports = $query->latest()->get();

        return view(
            'reports.payment-collection-dues-summary-report.index',
            compact('customerIds', 'reports')
        );
    }

    public function export(Request $request)
    {
        $query = CustomerBooking::with([
            'primaryDetail',
            'plotSaleDetail.plotDetail',
            'payment',
            'payments',
        ]);
        if ($request->filled('date')) {
            $query->whereDate('created_at', Carbon::parse($request->date));
        }
        if ($request->filled('customer_id')) {
            $query->where('id', $request->customer_id);
        }

        $reports = $query->latest()->get();

        return $this->excelExportService->export($reports, 'payment-collection-dues-summary-report',
            ['Customer ID', 'Customer Name', 'Booking ID', 'Total Cost', 'Paid Amount', 'Due Amount', 'Plot No'],
            function ($report) {
                $paidAmount = $report->payments
                    ->whereIn('payment_status', ['paid', 'cleared'])
                    ->sum('paid_amount');
                $finalAmount = $report->payment?->net_payable_amount ?? 0;
                $dueAmount = $finalAmount - $paidAmount;

                return [
                    $report->customer_code ?? 'N/A',
                    $report->primaryDetail?->name ?? 'N/A',
                    $report->booking_code ?? 'N/A',
                    $finalAmount,
                    $paidAmount,
                    $dueAmount,
                    $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                ];
            }
        );
    }
}
