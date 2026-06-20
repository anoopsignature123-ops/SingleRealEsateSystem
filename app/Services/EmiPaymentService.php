<?php

namespace App\Services;

use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;

class EmiPaymentService
{

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $lastId = (CustomerPayment::max('id') ?? 0) + 1;

            $data['receipt_number'] = 'RCP-'.date('Ymd').'-'.str_pad($lastId, 4, '0', STR_PAD_LEFT);

            $oldPayment = CustomerPayment::where('customer_booking_id', $data['customer_booking_id'])
                ->where('plot_sale_detail_id', $data['plot_sale_detail_id'])
                ->latest()
                ->first();

            $oldDueAmount = round((float) ($oldPayment->due_amount ?? 0), 2);
            $paidAmount = round((float) ($data['booking_amount'] ?? 0), 2);

            $newDueAmount = round(max(0, $oldDueAmount - $paidAmount), 2);

            $fixedMonthlyEmi = round((float) ($oldPayment->after_booking_payable_amount ?? 0), 2);

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
            $isHoldPayment = in_array($data['payment_mode'], ['cheque', 'dd']);
            $data['booking_status'] = $isHoldPayment ? 'hold' : 'booked';
            $data['payment_status'] = (!$isHoldPayment && $newDueAmount <= 0) ? 'cleared' : 'pending';
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
            if (!$isHoldPayment && $newDueAmount <= 0) {
                CustomerPayment::where('customer_booking_id', $data['customer_booking_id'])
                    ->where('plot_sale_detail_id', $data['plot_sale_detail_id'])
                    ->where('plan_type', 'emi_plan')
                    ->where('booking_status', 'booked')
                    ->update(['payment_status' => 'cleared']);
            }
            return CustomerPayment::create($data);
        });
    }
}
