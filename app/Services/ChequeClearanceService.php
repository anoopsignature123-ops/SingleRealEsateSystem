<?php

namespace App\Services;

use App\Models\CustomerPayment;
use App\Models\PlotDetail;

class ChequeClearanceService
{
    public function store(array $data)
    {
        $paymentIds = explode(',', $data['payment_ids']);

        $payments = CustomerPayment::whereIn('id', $paymentIds)->get();

        foreach ($payments as $payment) {

            $isCleared = $data['cheque_status'] === 'cleared';

            $payment->update([
                'cheque_status' => $data['cheque_status'],
                'booking_status' => $isCleared ? 'booked' : 'hold',
                'cheque_reason' => $data['cheque_reason'] ?? null,
                'cheque_date' => $data['cheque_date'],
            ]);

            if ($isCleared && $payment->plotSaleDetail?->plot_detail_id) {
                PlotDetail::where('id', $payment->plotSaleDetail->plot_detail_id)
                    ->update([
                        'status' => 'booked',
                    ]);
            }
        }

        return true;
    }
}