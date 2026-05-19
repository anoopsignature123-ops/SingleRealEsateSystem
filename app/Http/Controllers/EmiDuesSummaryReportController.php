<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\CustomerBooking;
use App\Services\ExcelExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmiDuesSummaryReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $sponsors = Associate::select('id', 'associate_id', 'associate_name')->get();
        $customers = CustomerBooking::select('id', 'customer_code')->get();
        $reports = collect();
        if ($request->has('search')) {
            $query = CustomerBooking::with([
                'primaryDetail',
                'associate',
                'plotSaleDetail.block',
                'plotSaleDetail.plotDetail',
                'payments',
                'payment',
            ]);
            if ($request->filled('sponsor_id')) {
                $query->where('associate_id', $request->sponsor_id);
            }
            if ($request->filled('customer_id')) {
                $query->where('id', $request->customer_id);
            }
            $reports = $query->latest()->get();
            if ($request->filled('due_emi')) {
                $reports = $reports->filter(function ($report) use ($request) {
                    $payment = $report->payment;
                    $payableAmount = $payment?->net_payable_amount ?? 0;
                    $paidAmount = $report->payments->sum('booking_amount');
                    $months = $payment?->emi_months ?? 1;
                    $installmentAmount = $months > 0 ? $payableAmount / $months : 0;
                    $bookingDate = optional($report->plotSaleDetail)->booking_date;
                    if (! $bookingDate) {
                        return false;
                    }
                    $today = Carbon::today();
                    $emiTillDate = Carbon::parse($bookingDate)->diffInMonths($today);
                    $noOfPaidEmi = $installmentAmount > 0 ? floor($paidAmount / $installmentAmount) : 0;
                    $duesEmi = $emiTillDate - $noOfPaidEmi;
                    if ($request->due_emi == 'greater') {
                        return $duesEmi > 0;
                    }
                    if ($request->due_emi == 'equal') {
                        return $duesEmi == 0;
                    }
                    if ($request->due_emi == 'less') {
                        return $duesEmi < 0;
                    }

                    return true;
                });
            }
        }

        return view('reports.emi-dues-summary-report.index', compact('sponsors', 'customers', 'reports'));
    }

    public function export(Request $request)
    {
        $query = CustomerBooking::with([
            'primaryDetail',
            'associate',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
            'payments',
            'payment',
        ]);
        if ($request->filled('sponsor_id')) {
            $query->where('associate_id', $request->sponsor_id);
        }
        if ($request->filled('customer_id')) {
            $query->where('id', $request->customer_id);
        }
        $reports = $query->latest()->get();
        if ($request->filled('due_emi')) {
            $reports = $reports->filter(function ($report) use ($request) {
                $payment = $report->payment;
                $payableAmount = (float) ($payment?->net_payable_amount ?? 0);
                $paidAmount = (float) $report->payments->sum('booking_amount');
                $months = (int) ($payment?->emi_months ?? 0);
                $installmentAmount = 0;
                if ($months > 0) {
                    $installmentAmount = $payableAmount / $months;
                }
                $bookingDate = optional($report->plotSaleDetail)->booking_date;
                if (! $bookingDate) {
                    return false;
                }
                $tillDate = $request->filled('till_date') ? Carbon::parse($request->till_date) : now();
                $emiTillDate = Carbon::parse($bookingDate)->diffInMonths($tillDate);
                if ($emiTillDate > $months) {
                    $emiTillDate = $months;
                }
                $noOfPaidEmi = 0;
                if ($installmentAmount > 0) {
                    $noOfPaidEmi = floor($paidAmount / $installmentAmount);
                }
                $duesEmi = (int) ($emiTillDate - $noOfPaidEmi);
                if ($request->due_emi == 'greater') {
                    return $duesEmi > 0;
                }
                if ($request->due_emi == 'equal') {
                    return $duesEmi == 0;
                }
                if ($request->due_emi == 'less') {
                    return $duesEmi < 0;
                }

                return true;
            });
        }

        return $this->excelExportService->export($reports, 'emi-dues-summary-report',
            [
                'Booking Id',
                'Customer Id',
                'Customer Name',
                'Booking Date',
                'Block',
                'Plot No',
                'Plot Rate',
                'Plot Area',
                'Plot Cost',
                'PLC Amt',
                'Payable Amount',
                'Paid Amount',
                'Installment Amount',
                'Months',
                'EMI Till Date',
                'No Of EMI Till Date',
                'No Of EMI Paid',
                'Agent Id',
                'Dues EMI',
            ],
            function ($report) use ($request) {
                $plotSale = $report->plotSaleDetail;
                $payment = $report->payment;
                $payableAmount = (float) ($payment?->net_payable_amount ?? 0);
                $paidAmount = (float) $report->payments->sum('booking_amount');
                $months = (int) ($payment?->emi_months ?? 0);
                $installmentAmount = 0;
                if ($months > 0) {
                    $installmentAmount = $payableAmount / $months;
                }
                $bookingDate = $plotSale?->booking_date;
                $tillDate = $request->filled('till_date') ? Carbon::parse($request->till_date) : now();
                $emiTillDate = 0;
                if ($bookingDate) {
                    $bookingCarbon = Carbon::parse($bookingDate);
                    $emiTillDate = $bookingCarbon->diffInMonths($tillDate);
                    if ($emiTillDate > $months) {
                        $emiTillDate = $months;
                    }
                }
                $noOfPaidEmi = 0;
                if ($installmentAmount > 0) {
                    $noOfPaidEmi = floor($paidAmount / $installmentAmount);
                }
                $duesEmi = (int) ($emiTillDate - $noOfPaidEmi);

                return [
                    $report->booking_code ?? 'N/A',
                    $report->customer_code ?? 'N/A',
                    $report->primaryDetail?->name ?? 'N/A',
                    $bookingDate ? Carbon::parse($bookingDate)->format('d-m-Y') : 'N/A',
                    $plotSale?->block?->block ?? 'N/A',
                    $plotSale?->plotDetail?->plot_number ?? 'N/A',
                    $plotSale?->plot_rate ?? 0,
                    $plotSale?->plot_area ?? 0,
                    $plotSale?->plot_cost ?? 0,
                    $plotSale?->plc_amount ?? 0,
                    $payableAmount,
                    $paidAmount,
                    round($installmentAmount, 2),
                    $months,
                    $tillDate->format('d-m-Y'),
                    $emiTillDate,
                    $noOfPaidEmi,
                    $report->associate?->associate_id ?? 'N/A',
                    $duesEmi,
                ];
            }
        );
    }
}
