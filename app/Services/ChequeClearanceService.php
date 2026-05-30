<?php

namespace App\Services;

use App\Models\CustomerPayment;
use App\Models\PlotSaleDetail;

class ChequeClearanceService
{
    public function store(array $data)
    {
        $paymentIds = explode(',', $data['payment_ids']);
        CustomerPayment::whereIn('id', $paymentIds)->update([
            'cheque_status' => $data['cheque_status'],
            'payment_status' => ($data['cheque_status'] === 'cleared') ? 'booked' : 'hold',
            'cheque_reason' => $data['cheque_reason'] ?? null,
            'cheque_date' => $data['cheque_date'],
        ]);
        if ($data['cheque_status'] === 'cleared') {
            $plotSaleDetailIds = CustomerPayment::whereIn('id', $paymentIds)->pluck('plot_sale_detail_id')->unique();
            PlotSaleDetail::whereIn('id', $plotSaleDetailIds)->update(['status' => 'booked',]);
        }
        return true;
    }
}
