<?php

namespace App\Services;

use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;

class EmiPaymentService
{
    // public function store(array $data)
    // {
    // return DB::transaction(function () use ($data) {
    //     $lastId = CustomerPayment::max('id') + 1;
    //     $receiptNumber = 'RCP-'.date('Ymd').'-'.str_pad($lastId, 4, '0', STR_PAD_LEFT);
    //     $data['receipt_number'] = $receiptNumber;
    //     $data['plan_type'] = 'emi_plan';
    //     $data['transaction_category'] = 'emi_payment';
    //     $data['payment_status'] = in_array($data['payment_mode'], ['cheque', 'dd']) ? 'hold' : 'booked';
    //     $data['bank_name'] = $data['bank_name'] ?? null;
    //     $data['account_number'] = $data['account_number'] ?? null;
    //     $data['branch_name'] = $data['branch_name'] ?? null;
    //     $data['cheque_number'] = $data['cheque_number'] ?? null;
    //     $data['cheque_date'] = $data['cheque_date'] ?? null;
    //     $data['dd_number'] = $data['dd_number'] ?? null;
    //     $data['transaction_number'] = $data['transaction_number'] ?? null;
    //     $data['remark'] = $data['remark'] ?? null;
    //     return CustomerPayment::create($data);
    // });
    // }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $lastId = CustomerPayment::max('id') + 1;

            $data['receipt_number'] = 'RCP-'.date('Ymd').'-'.str_pad($lastId, 4, '0', STR_PAD_LEFT);

            $oldPayment = CustomerPayment::where('customer_booking_id', $data['customer_booking_id'])
                ->where('plot_sale_detail_id', $data['plot_sale_detail_id'])
                ->latest()
                ->first();

            $oldDueAmount = (float) ($oldPayment->due_amount ?? 0);
            $paidAmount = (float) ($data['booking_amount'] ?? 0);

            $newDueAmount = max(0, $oldDueAmount - $paidAmount);

            $fixedMonthlyEmi = (float) ($oldPayment->after_booking_payable_amount ?? 0);

            // Remaining EMI Months
            $remainingEmiMonths = $fixedMonthlyEmi > 0
                ? ceil($newDueAmount / $fixedMonthlyEmi)
                : 0;
            $data['plan_type'] = 'emi_plan';
            $data['emi_months'] = $remainingEmiMonths;
            $data['paid_amount'] = $paidAmount;
            $data['due_amount'] = $newDueAmount;
            $data['after_booking_payable_amount'] = $fixedMonthlyEmi;
            $data['net_payable_amount'] = $newDueAmount;
            $data['transaction_category'] = 'emi_payment';
            $data['emi_date'] = now();
            $data['booking_status'] = in_array($data['payment_mode'], ['cheque', 'dd'])
    ? 'hold'
    : 'booked';

            $data['payment_status'] = $newDueAmount <= 0
    ? 'cleared'
    : 'pending';

            $fields = [
                'bank_name',
                'account_number',
                'branch_name',
                'cheque_number',
                'cheque_date',
                'dd_number',
                'transaction_number',
                'remark',
            ];

            foreach ($fields as $field) {
                $data[$field] = $data[$field] ?? null;
            }

            // Due Amount 0 => All Payments Cleared
            if ($newDueAmount <= 0) {

                CustomerPayment::where('customer_booking_id', $data['customer_booking_id'])
                    ->where('plot_sale_detail_id', $data['plot_sale_detail_id'])
                    ->update([
                        'payment_status' => 'cleared',
                    ]);
            }

            return CustomerPayment::create($data);
        });
    }
}