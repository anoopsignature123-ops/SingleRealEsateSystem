<?php

namespace App\Services;

use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Models\PlotSaleDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class OneTimePaymentService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $booking = CustomerBooking::findOrFail($data['customer_booking_id']);
            $plotSale = PlotSaleDetail::where('id', $data['plot_sale_detail_id'])
                ->where('customer_booking_id', $booking->id)
                ->firstOrFail();
            if (! $plotSale) {
                throw new Exception('Plot sale details not found.');
            }
            $totalPlotCost = round((float) $plotSale->total_plot_cost, 2);
            $alreadyPaid = round((float) CustomerPayment::where('customer_booking_id', $booking->id)
                ->where('plot_sale_detail_id', $plotSale->id)
                ->where('plan_type', 'full_payment')
                ->where('booking_status', 'booked')
                ->sum('paid_amount'), 2);
            $payingAmount = round((float) $data['booking_amount'], 2);
            $remainingDue = round($totalPlotCost - $alreadyPaid, 2);
            if ($remainingDue <= 0) {
                throw new Exception('This booking is already fully paid.');
            }
            if (($payingAmount - $remainingDue) > 0.01) {
                throw new Exception('Payment amount cannot exceed due amount.');
            }
            if ($payingAmount > $remainingDue) {
                $payingAmount = $remainingDue;
            }
            $lastId = (CustomerPayment::max('id') ?? 0) + 1;
            $autoReceiptNumber = 'RCP-'.date('Ymd').'-'.str_pad($lastId, 4, '0', STR_PAD_LEFT);
            $finalDue = round($remainingDue - $payingAmount, 2);
            $isHoldPayment = in_array($data['payment_mode'], ['cheque', 'dd']);
            $bookingStatus = $isHoldPayment ? 'hold' : 'booked';
            $paymentStatus = (!$isHoldPayment && $finalDue <= 0) ? 'cleared' : 'pending';

            if (!$isHoldPayment && $finalDue <= 0) {
                CustomerPayment::where('customer_booking_id', $booking->id)
                    ->where('plot_sale_detail_id', $plotSale->id)
                    ->where('plan_type', 'full_payment')
                    ->where('booking_status', 'booked')
                    ->update(['payment_status' => 'cleared']);
            }

            return CustomerPayment::create([
                'customer_booking_id' => $booking->id,
                'plot_sale_detail_id' => $data['plot_sale_detail_id'],
                'receipt_number' => $autoReceiptNumber,
                'manual_receipt_number' => $data['manual_receipt_number'] ?? null,
                'plan_type' => 'full_payment',
                'transaction_category' => 'one_time',
                'booking_amount' => $payingAmount,
                'paid_amount' => $payingAmount,
                'due_amount' => $finalDue,
                'net_payable_amount' => $totalPlotCost,
                'remark' => $data['remark'] ?? null,
                'payment_mode' => $data['payment_mode'],
                'account_number' => $data['account_number'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'branch_name' => $data['branch_name'] ?? null,
                'cheque_number' => $data['cheque_number'] ?? null,
                'cheque_date' => $data['cheque_date'] ?? null,
                'dd_number' => $data['dd_number'] ?? null,
                'transaction_number' => $data['transaction_number'] ?? null,
                'booking_status' => $bookingStatus,
                'payment_status' => $paymentStatus,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
