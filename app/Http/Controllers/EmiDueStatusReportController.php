<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class EmiDueStatusReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail.correspondenceDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.plotDetail',
        ])->where('plan_type', 'emi_plan');

        if ($request->customer_name) {
            $query->whereHas('customerBooking.primaryDetail',
                function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->customer_name.'%');
                }
            );
        }

        if ($request->mobile) {
            $query->whereHas('customerBooking.primaryDetail.correspondenceDetail',
                function ($q) use ($request) {
                    $q->where('mobile_number', 'like', '%' . $request->mobile . '%');
                }
            );
        }
        if ($request->due_date) {
            $query->whereDate('emi_date', $request->due_date);
        }
        $emis = $query->latest()->get();
        foreach ($emis as $emi) {
            $emi->paid_installment =
                CustomerPayment::where('customer_booking_id', $emi->customer_booking_id)
                    ->where('payment_status', 'emi')->count();
        }

        return view('reports.emi_due_status_report.index', compact('emis'));
    }

    public function export(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail.correspondenceDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.plotDetail',
        ])->where('plan_type', 'emi_plan');

        if ($request->customer_name) {
            $query->whereHas('customerBooking.primaryDetail',
                function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->customer_name.'%');
                }
            );
        }
        if ($request->mobile) {
            $query->whereHas('customerBooking.primaryDetail.correspondenceDetail',
                function ($q) use ($request) {
                    $q->where('mobile_number', 'like', '%' . $request->mobile . '%');
                }
            );
        }
        if ($request->due_date) {
            $query->whereDate('emi_date', $request->due_date);
        }
        $emis = $query->latest()->get();
        foreach ($emis as $emi) {
            $emi->paid_installment =
                CustomerPayment::where('customer_booking_id', $emi->customer_booking_id)
                    ->where('payment_status', 'emi')->count();
        }

        return $this->excelExportService->export($emis, 'emi-due-status-report',
            [
                'Agent ID',
                'Customer ID',
                'Booking ID',
                'Customer Name',
                'Project',
                'Plot No',
                'Payable Amount',
                'Paid Amount',
                'Due Amount',
                'Installment',
                'EMI Date',
                'Status',
            ],
            function ($emi) {
                $customer = $emi->customerBooking;
                $status = $emi->due_amount > 0 ? 'Pending' : 'Completed';

                return [
                    $customer?->associate_code ?? 'N/A',
                    $customer?->customer_code ?? 'N/A',
                    $customer?->booking_code ?? 'N/A',
                    $customer?->primaryDetail?->name ?? 'N/A',
                    $emi->plotSaleDetail?->project?->name ?? 'N/A',
                    $emi->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    $emi->booking_amount ?? 0,
                    $emi->net_payable_amount ?? 0,
                    $emi->due_amount ?? 0,
                    $emi->paid_installment.'/'.$emi->emi_months,
                    $emi->emi_date ? $emi->emi_date->format('d-m-Y') : 'N/A',
                    $status,
                ];
            }
        );
    }
}
