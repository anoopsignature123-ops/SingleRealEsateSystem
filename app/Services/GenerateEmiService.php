<?php

namespace App\Services;

use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Models\PlotSaleDetail;
use Illuminate\Validation\ValidationException;

class GenerateEmiService
{
    public function getCustomers()
    {
        return CustomerBooking::with('primaryDetail')
            ->whereHas('plotSaleDetails', function ($query) {
                $query->whereNotNull('booking_code')
                    ->whereHas('payments', function ($paymentQuery) {
                        $paymentQuery->where('plan_type', 'emi_plan');
                    });
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
            ->whereNotNull('booking_code')
            ->whereHas('payments', function ($paymentQuery) {
                $paymentQuery->where('plan_type', 'emi_plan');
            });

        if ($customerId) {
            $query->where('customer_booking_id', $customerId);
        }

        return $query->latest()->get()
            ->groupBy(function (PlotSaleDetail $plotSale) {
                return implode('|', [
                    $plotSale->customer_booking_id,
                    $plotSale->booking_code ?: 'plot-'.$plotSale->id,
                ]);
            })
            ->map(function ($group) {
                $first = $group->first();
                $plotSales = $group->values();
                $first->group_plot_sale_ids = $plotSales->pluck('id')->implode(',');
                $first->group_plot_count = $plotSales->count();
                $first->group_projects = $plotSales
                    ->map(fn ($sale) => $sale->project?->name)
                    ->filter()
                    ->unique()
                    ->implode(', ');
                $first->group_blocks = $plotSales
                    ->map(fn ($sale) => $sale->block?->block)
                    ->filter()
                    ->unique()
                    ->implode(', ');
                $first->group_plot_numbers = $plotSales
                    ->map(fn ($sale) => $sale->plotDetail?->plot_number)
                    ->filter()
                    ->unique()
                    ->implode(', ');
                $first->group_total_cost = round((float) $plotSales->sum(fn ($sale) => (float) ($sale->total_plot_cost ?? 0)), 2);
                $first->group_paid = round((float) $plotSales->sum(function ($sale) {
                    return (float) ($sale->payments ?? collect())
                        ->whereIn('payment_status', ['paid', 'cleared'])
                        ->sum('paid_amount');
                }), 2);
                $first->group_due = max(0, round($first->group_total_cost - $first->group_paid, 2));
                $latestPayments = $plotSales
                    ->map(fn ($sale) => ($sale->payments ?? collect())->sortByDesc('id')->first())
                    ->filter();
                $emiMonths = $latestPayments->pluck('emi_months')->filter()->unique()->values();
                $monthlyEmi = $latestPayments->sum(fn ($payment) => (float) ($payment->after_booking_payable_amount ?? 0));

                $first->group_current_emi_months = $emiMonths->max();
                $first->group_monthly_emi = round((float) $monthlyEmi, 2);
                $first->group_is_emi_generated = $latestPayments->isNotEmpty()
                    && $latestPayments->every(fn ($payment) => (int) ($payment->emi_months ?? 0) > 0 && (float) ($payment->after_booking_payable_amount ?? 0) > 0);
                $first->group_can_generate = $first->group_due > 0 && $latestPayments->count() === $plotSales->count();

                return $first;
            })
            ->sortByDesc('id')
            ->values();
    }

    public function generate($plotSaleDetailId, array $data)
    {
        $plotSale = PlotSaleDetail::with([
            'customerBooking',
            'payments',
        ])->find($plotSaleDetailId);

        if (! $plotSale) {
            throw ValidationException::withMessages([
                'emi_months' => 'Selected plot booking was not found. Please refresh and try again.',
            ]);
        }

        $booking = $plotSale->customerBooking;

        if (! $booking) {
            throw ValidationException::withMessages([
                'emi_months' => 'Customer booking not found for this plot.',
            ]);
        }

        $plotSales = PlotSaleDetail::with('payments')
            ->where('customer_booking_id', $booking->id)
            ->when($plotSale->booking_code, function ($query) use ($plotSale) {
                $query->where('booking_code', $plotSale->booking_code);
            }, function ($query) use ($plotSale) {
                $query->where('id', $plotSale->id);
            })
            ->whereHas('payments', function ($paymentQuery) {
                $paymentQuery->where('plan_type', 'emi_plan');
            })
            ->get();

        if ($plotSales->isEmpty()) {
            throw ValidationException::withMessages([
                'emi_months' => 'EMI plot booking was not found. Please refresh and try again.',
            ]);
        }

        $totalDueAmount = round((float) $plotSales->sum(function (PlotSaleDetail $sale) {
            $totalPlotCost = (float) ($sale->total_plot_cost ?? 0);
            $totalPaid = (float) $sale->payments()
                ->whereIn('payment_status', ['paid', 'cleared'])
                ->sum('paid_amount');

            return max(0, $totalPlotCost - $totalPaid);
        }), 2);

        if ($totalDueAmount <= 0) {
            throw ValidationException::withMessages([
                'emi_months' => 'No due amount available for EMI generation.',
            ]);
        }

        $emiMonths = (int) ($data['emi_months'] ?? 0);

        if ($emiMonths <= 0) {
            throw ValidationException::withMessages([
                'emi_months' => 'EMI months must be greater than zero.',
            ]);
        }

        foreach ($plotSales as $sale) {
            $totalPlotCost = (float) ($sale->total_plot_cost ?? 0);
            $totalPaid = (float) $sale->payments()
                ->whereIn('payment_status', ['paid', 'cleared'])
                ->sum('paid_amount');
            $dueAmount = round(max(0, $totalPlotCost - $totalPaid), 2);

            if ($dueAmount <= 0) {
                continue;
            }

            $latestPayment = CustomerPayment::where('customer_booking_id', $booking->id)
                ->where('plot_sale_detail_id', $sale->id)
                ->latest()
                ->first();

            if (! $latestPayment) {
                throw ValidationException::withMessages([
                    'emi_months' => 'Payment record not found for this booking. Please collect booking payment first.',
                ]);
            }

            if ($latestPayment->plan_type !== 'emi_plan') {
                throw ValidationException::withMessages([
                    'emi_months' => 'EMI can be generated only for EMI plan bookings.',
                ]);
            }

            $latestPayment->update([
                'plan_type' => 'emi_plan',
                'emi_months' => $emiMonths,
                'after_booking_payable_amount' => round($dueAmount / $emiMonths, 2),
                'due_amount' => $dueAmount,
                'net_payable_amount' => $dueAmount,
                'payment_status' => $latestPayment->booking_status === 'hold' ? 'hold' : 'paid',
            ]);
        }

        return true;
    }
}
