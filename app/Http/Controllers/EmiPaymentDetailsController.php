<?php

namespace App\Http\Controllers;

use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class EmiPaymentDetailsController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function getCustomerDetails($id)
    {
        $customer = CustomerBooking::with('primaryDetail')->find($id);

        return response()->json(['name' => $customer?->primaryDetail?->name ?? '']);
    }

    public function index(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.plotDetail',
        ])->where('plan_type', 'emi_plan');
        if ($request->customer_id) {
            $query->whereHas('customerBooking', function ($q) use ($request) {
                $q->where('id', $request->customer_id);
            });
        }
        if ($request->customer_name) {
            $query->whereHas('customerBooking.primaryDetail', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->customer_name.'%');
            });
        }
        if ($request->payment_mode) {
            $query->where('payment_mode', $request->payment_mode);
        }
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        $payments = $query->latest()->get();
        $customerIds = CustomerBooking::with('primaryDetail')->get();

        return view('reports.emi-payment-details.index',
            compact('payments', 'customerIds')
        );
    }

    public function export(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.plotDetail',
        ])->where('plan_type', 'emi_plan');
        if ($request->customer_id) {
            $query->whereHas('customerBooking', function ($q) use ($request) {
                $q->where('id', $request->customer_id);
            });
        }
        if ($request->customer_name) {
            $query->whereHas('customerBooking.primaryDetail', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->customer_name.'%');
            });
        }
        if ($request->payment_mode) {
            $query->where('payment_mode', $request->payment_mode);
        }
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        $payments = $query->latest()->get();

        return $this->excelExportService->export($payments, 'emi-payment-details-report',
            ['Booking ID', 'Customer Name', 'Project Name', 'Plot No', 'Inst Amount', 'Paid Amount', 'Pay Mode', 'Status',
                'Pay Date', 'Remark'],
            function ($payment) {
                $status = $payment->cheque_status ?? 'CLEAR';

                return [
                    $payment->customerBooking?->booking_code ?? 'N/A',
                    $payment->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $payment->plotSaleDetail?->project?->name ?? 'N/A',
                    $payment->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    $payment->after_booking_payable_amount ?? 0,
                    $payment->booking_amount ?? 0,
                    ucfirst($payment->payment_mode ?? 'N/A'),
                    strtoupper($status),
                    $payment->created_at
                        ? $payment->created_at->format('d-m-Y')
                        : 'N/A',
                    $payment->remark ?? 'N/A',
                ];
            }
        );
    }
}
