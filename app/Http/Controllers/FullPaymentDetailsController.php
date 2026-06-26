<?php

namespace App\Http\Controllers;

use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class FullPaymentDetailsController extends Controller
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
        ])->where('plan_type', 'full_payment');
        if ($request->customer_id) {
            $query->whereHas('customerBooking', function ($q) use ($request) {
                $q->where('id', $request->customer_id);
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

        return view('reports.full-payment-details.index', compact('payments', 'customerIds'));
    }

    public function export(Request $request)
    {
        $query = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.plotDetail',
        ])->where('plan_type', 'full_payment');
        if ($request->customer_id) {
            $query->whereHas('customerBooking', function ($q) use ($request) {
                $q->where('id', $request->customer_id);
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

        return $this->excelExportService->export(
            $payments,
            'full-payment-details-report',
            [
                'Booking ID',
                'Customer Name',
                'Project Name',
                'Plot No',
                'PLC Amount',
                'Other Charges',
                'Discount',
                'Payable Amount',
                'Paid Amount',
                'Pay Mode',
                'Pay Type',
                'Status',
                'Cheque Date',
                'Pay Date',
                'Remark',
            ],
            function ($payment) {
                $plotSale = $payment->plotSaleDetail;

                return [
                    $payment->customerBooking?->booking_code ?? 'N/A',
                    $payment->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $plotSale?->project?->name ?? 'N/A',
                    $plotSale?->plotDetail?->plot_number ?? 'N/A',
                    $plotSale?->plc_amount ?? 0,
                    $plotSale?->other_charges ?? 0,
                    $plotSale?->coupon_discount ?? 0,
                    $payment->net_payable_amount ?? 0,
                    $payment->booking_amount ?? 0,
                    strtoupper($payment->payment_mode ?? 'N/A'),
                    $payment->transaction_category === 'booking_fee' ? 'Booking Amount' : 'Full Payment',
                    strtoupper($payment->cheque_status ?? 'CLEAR'),
                    $payment->cheque_date ? $payment->cheque_date->format('d-m-Y') : 'N/A',
                    $payment->created_at ? $payment->created_at->format('d-m-Y') : 'N/A',
                    $payment->remark ?? 'N/A',
                ];
            }
        );
    }
}
