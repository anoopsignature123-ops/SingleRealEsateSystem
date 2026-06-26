<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class NewBookingPaymentDetailsReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $associates = Associate::select('id', 'associate_id', 'associate_name')->get();
        $reports = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'customerBooking.associate',
            'plotSaleDetail.plotDetail',
        ])->where('transaction_category', 'booking_fee')->latest();
        if ($request->filled('associate_id')) {
            $reports->whereHas('customerBooking', function ($q) use ($request) {
                $q->where('associate_id', $request->associate_id);
            });
        }
        if ($request->filled('from_date')) {
            $reports->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $reports->whereDate('created_at', '<=', $request->to_date);
        }
        $reports = $reports->get();

        return view('reports.new-booking-payment-details-report.index', compact('associates', 'reports'));
    }

    public function export(Request $request)
    {
        $reports = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'customerBooking.associate',
            'plotSaleDetail.plotDetail',
        ])->where('transaction_category', 'booking_fee')->latest();
        if ($request->filled('associate_id')) {
            $reports->whereHas('customerBooking', function ($q) use ($request) {
                $q->where('associate_id', $request->associate_id);
            });
        }
        if ($request->filled('from_date')) {
            $reports->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $reports->whereDate('created_at', '<=', $request->to_date);
        }
        $reports = $reports->get();

        return $this->excelExportService->export($reports, 'new-booking-payment-details-report',
            [
                'Associate ID',
                'Associate Name',
                'Customer ID',
                'Customer Name',
                'Booking ID',
                'Plot No',
                'Payment Mode',
                'Receipt No',
                'Paid Amount',
                'Date',
            ],
            function ($report) {
                return [
                    $report->customerBooking?->associate?->associate_id ?? 'N/A',
                    $report->customerBooking?->associate?->associate_name ?? 'N/A',
                    $report->customerBooking?->customer_code ?? 'N/A',
                    $report->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $report->customerBooking?->booking_code ?? 'N/A',
                    $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    ucfirst($report->payment_mode ?? 'N/A'),
                    $report->receipt_number ?? 'N/A',
                    $report->booking_amount ?? 0,
                    $report->created_at?->format('d-m-Y') ?? 'N/A',
                ];
            }
        );
    }
}
