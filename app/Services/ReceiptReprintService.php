<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CustomerPayment;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptReprintService
{
    public function search($plotId, $customerBookingId)
    {
        return CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ])
            ->where('customer_booking_id', $customerBookingId)
            ->whereHas('plotSaleDetail', function ($query) use ($plotId) {
                $query->where('plot_detail_id', $plotId);
            })
            ->latest()
            ->get();
    }

    public function downloadPdf($paymentId)
    {
        $payment = CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ])->findOrFail($paymentId);

        $company = Company::where('status', '1')->first();

        $pdf = Pdf::loadView('payment.receipt-reprint.pdf', compact('payment', 'company'));

        return $pdf->download(
            'receipt-' . ($payment->receipt_number ?? 'RCP-' . $paymentId) . '.pdf'
        );
    }
}