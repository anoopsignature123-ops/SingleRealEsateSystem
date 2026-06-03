<?php

namespace App\Services;

use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Models\PlotSaleDetail;

class GenerateEmiService
{
    public function getCustomers()
    {
        return CustomerBooking::with('primaryDetail')
            ->whereHas('plotSaleDetails', function ($query) {
                $query->whereNotNull('booking_code');
            })
            ->orderBy('customer_code')
            ->get();
    }

    public function getList($customerId = null)
    {
        $query = PlotSaleDetail::with([
            'customerBooking.primaryDetail',
            'customerBooking.associate',
            'project',
            'block',
            'plotDetail',
            'payments',
        ])
            ->whereNotNull('booking_code');

        if ($customerId) {
            $query->where('customer_booking_id', $customerId);
        }

        return $query->latest()->get();
    }

    public function generate($plotSaleDetailId, array $data)
    {
        $plotSale = PlotSaleDetail::with([
            'customerBooking',
            'payments',
        ])->findOrFail($plotSaleDetailId);

        $booking = $plotSale->customerBooking;

        if (! $booking) {
            abort(404, 'Customer booking not found.');
        }

        $totalPlotCost = (float) ($plotSale->total_plot_cost ?? 0);

        $totalPaid = (float) $plotSale->payments()
            ->where('booking_status', 'booked')
            ->sum('paid_amount');

        $dueAmount = max(0, $totalPlotCost - $totalPaid);

        if ($dueAmount <= 0) {
            abort(422, 'No due amount available for EMI generation.');
        }

        $emiMonths = (int) ($data['emi_months'] ?? 0);

        if ($emiMonths <= 0) {
            abort(422, 'EMI months must be greater than zero.');
        }

        $emiAmount = round($dueAmount / $emiMonths, 2);

        $latestPayment = CustomerPayment::where('customer_booking_id', $booking->id)
            ->where('plot_sale_detail_id', $plotSale->id)
            ->latest()
            ->first();

        if (! $latestPayment) {
            abort(404, 'Payment record not found.');
        }

        $latestPayment->update([
            'plan_type' => 'emi_plan',
            'emi_months' => $emiMonths,
            'after_booking_payable_amount' => $emiAmount,
            'due_amount' => $dueAmount,
            'net_payable_amount' => $dueAmount,
            'payment_status' => 'pending',
        ]);

        return true;
    }
}
