<?php

namespace App\Http\Controllers;

use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DuesInstallmentReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $customerIds = CustomerBooking::select('id', 'customer_code')->get();
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'customerBooking.associate',
            'plotSaleDetail.plotDetail',
        ]);
        if ($request->filled('date')) {
            $query->whereDate('created_at', Carbon::parse($request->date));
        }
        if ($request->filled('customer_id')) {
            $query->whereHas('customerBooking',
                function ($q) use ($request) {
                    $q->where('id', $request->customer_id);
                }
            );
        }
        $reports = $query->latest()->get();

        return view('reports.dues-installment-report.index', compact('customerIds', 'reports')
        );
    }

    public function export(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'customerBooking.associate',
            'plotSaleDetail.plotDetail',
        ]);
        if ($request->filled('date')) {
            $query->whereDate('created_at', Carbon::parse($request->date));
        }
        if ($request->filled('customer_id')) {
            $query->whereHas('customerBooking',
                function ($q) use ($request) {
                    $q->where('id', $request->customer_id);
                }
            );
        }
        $reports = $query->latest()->get();

        return $this->excelExportService->export($reports, 'dues-installment-report',
            [
                'Agent ID',
                'Customer ID',
                'Customer Name',
                'Booking ID',
                'Booking Date',
                'Installment Amt',
                'Total Ins Amt',
                'Paid Ins Amt',
                'Balance Amt',
                'No Of Due Ins',
            ],
            function ($report) {
                $totalAmount = $report->net_payable_amount ?? 0;
                $paidAmount = $report->customerBooking?->payments?->sum('booking_amount') ?? 0;
                $balance = $totalAmount - $paidAmount;
                $emiMonths = $report->emi_months ?? 1;
                $installment = 0;
                if ($emiMonths > 0) {
                    $installment = $totalAmount / $emiMonths;
                }
                $dueInstallment = 0;
                if ($installment > 0) {
                    $dueInstallment = floor($balance / $installment);
                }

                return [
                    $report->customerBooking?->associate?->associate_code ?? 'N/A',
                    $report->customerBooking?->customer_code ?? 'N/A',
                    $report->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $report->customerBooking?->booking_code ?? 'N/A',
                    $report->customerBooking?->created_at?->format('d-m-Y') ?? 'N/A',
                    number_format($installment, 2, '.', ''),
                    number_format($totalAmount, 2, '.', ''),
                    number_format($paidAmount, 2, '.', ''),
                    number_format($balance, 2, '.', ''),
                    $dueInstallment,
                ];
            }
        );
    }
}
