<?php

namespace App\Services\Associate;

use App\Models\Associate;
use App\Models\CustomerBooking;
use App\Models\CustomerPayment;

class AssociateDashboardService
{
    public function getDashboardStats(int $associateId): array
    {
        $associate = Associate::findOrFail($associateId);
        $bookingIds = CustomerBooking::where('associate_id', $associateId)->pluck('id');
        $payments = CustomerPayment::whereIn('customer_booking_id', $bookingIds);
        $stats = [
            'total_business' => $payments->sum('booking_amount'),
            'confirmed_sales' => (clone $payments)->where('payment_status', 'confirmed')->sum('booking_amount'),
            'pending_sales' => (clone $payments)->where('payment_status', 'pending')->sum('booking_amount'),
        ];
        $recentLedgers = $payments->with(['customerBooking.plotSaleDetail.plotDetail'])->latest()->take(10)->get();

        return [
            'direct_count' => $associate->direct_count,
            'team_count' => $associate->downline_count,
            'total_business' => $stats['total_business'],
            'confirmed_sales' => $stats['confirmed_sales'],
            'pending_sales' => $stats['pending_sales'],
            'recent_ledgers' => $recentLedgers,
        ];
    }
}
