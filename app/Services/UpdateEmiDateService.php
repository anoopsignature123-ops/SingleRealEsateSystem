<?php

namespace App\Services;

use App\Models\CustomerPayment;

class UpdateEmiDateService
{
    public function getEmiPayments()
    {
        return CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ])
            ->where('plan_type', 'emi_plan')
            ->whereNotNull('plot_sale_detail_id')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('customer_payments')
                    ->where('plan_type', 'emi_plan')
                    ->whereNotNull('plot_sale_detail_id')
                    ->groupBy('customer_booking_id', 'plot_sale_detail_id');
            })
            ->latest()
            ->get();
    }

    public function store(array $data)
    {
        $paymentIds = explode(',', $data['payment_ids']);

        CustomerPayment::whereIn('id', $paymentIds)
            ->update([
                'emi_date' => $data['emi_date'],
            ]);

        return true;
    }
}
