<?php

namespace App\Services;

use App\Models\CustomerPayment;
use App\Models\PlotDetail;

class ChequeClearanceService
{
    public function store(array $data)
    {
        $paymentIds = collect(explode(',', $data['payment_ids']))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values();

        $payments = CustomerPayment::whereIn('id', $paymentIds)->get();

        foreach ($payments as $payment) {

            $isCleared = $data['cheque_status'] === 'cleared';

            $payment->update([
                'cheque_status' => $data['cheque_status'],
                'booking_status' => $isCleared ? 'booked' : 'hold',
                'payment_status' => $isCleared ? 'paid' : 'hold',
                'cheque_reason' => $data['cheque_reason'] ?? null,
                'cheque_date' => $data['cheque_date'],
            ]);

            if ($isCleared && $payment->plotSaleDetail?->plot_detail_id) {
                PlotDetail::where('id', $payment->plotSaleDetail->plot_detail_id)
                    ->update([
                        'status' => 'booked',
                    ]);

                $totalPlotCost = (float) ($payment->plotSaleDetail->total_plot_cost ?? 0);
                $confirmedPaid = (float) CustomerPayment::where('customer_booking_id', $payment->customer_booking_id)
                    ->where('plot_sale_detail_id', $payment->plot_sale_detail_id)
                    ->where('booking_status', 'booked')
                    ->sum('paid_amount');

                if ($totalPlotCost > 0 && $confirmedPaid >= $totalPlotCost) {
                    CustomerPayment::where('customer_booking_id', $payment->customer_booking_id)
                        ->where('plot_sale_detail_id', $payment->plot_sale_detail_id)
                        ->where('plan_type', $payment->plan_type)
                        ->where('booking_status', 'booked')
                        ->whereIn('payment_status', ['pending', 'paid'])
                        ->update(['payment_status' => 'cleared']);
                }
            }
        }

        return true;
    }
}
