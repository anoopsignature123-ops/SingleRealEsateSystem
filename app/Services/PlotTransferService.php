<?php

namespace App\Services;

use App\Models\Block;
use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Models\PlotDetail;
use App\Models\PlotSaleDetail;
use App\Models\PlotTransferHistory;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlotTransferService
{
    public function index($data)
    {
        $projects = Project::select('id', 'name')
            ->orderBy('name')
            ->get();

        $histories = PlotTransferHistory::with([
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
            'oldBooking.primaryDetail',
            'newBooking.primaryDetail',
            'createdBy',
        ])
            ->latest()
            ->get();

        return compact('projects', 'histories');
    }

    public function getBlocks($projectId)
    {
        return Block::where('project_id', $projectId)
            ->select('id', 'block')
            ->orderBy('block')
            ->get();
    }

    public function getPlots($blockId)
    {
        return PlotDetail::where('block_id', $blockId)
            ->where('status', 'booked')
            ->whereHas('plotSaleDetail.customerBooking', function ($query) {
                $query->where('status', '!=', 'cancelled');
            })
            ->select('id', 'plot_number')
            ->orderBy('plot_number')
            ->get();
    }

    public function getTransferCustomers($bookingId)
    {
        return CustomerBooking::with('primaryDetail')
            ->where('id', '!=', $bookingId)
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('customer_code')
            ->orderBy('customer_code')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->customer_code.' - '.(
                        $item->primaryDetail->name
                        ?? $item->customer_name
                        ?? 'N/A'
                    ),
                ];
            });
    }

    public function getBookingData($plotId)
    {
        $plotSale = PlotSaleDetail::with([
            'project',
            'block',
            'plotDetail',
            'customerBooking.primaryDetail',
            'payments',
        ])
            ->where('plot_detail_id', $plotId)
            ->whereHas('customerBooking', function ($query) {
                $query->where('status', '!=', 'cancelled');
            })
            ->first();

        if (! $plotSale) {
            return null;
        }

        $payments = $plotSale->payments;

        $latestPayment = $payments
            ->sortByDesc('id')
            ->first();

        $bookingPayment = $payments
            ->where('transaction_category', 'booking_fee')
            ->sortBy('id')
            ->first();

        $totalPlotCost = (float) ($plotSale->total_plot_cost ?? 0);

        $totalPaid = (float) $payments
            ->whereIn('payment_status', ['paid', 'cleared'])
            ->sum('paid_amount');

        $remainingAmount = max(0, $totalPlotCost - $totalPaid);

        $planType = $latestPayment->plan_type ?? 'full_payment';

        $emiMonths = 0;
        $paidEmis = 0;
        $dueMonths = 0;

        if ($planType === 'emi_plan') {
            $emiMonths = (int) ($latestPayment->emi_months ?? 0);

            $paidEmis = $payments
                ->where('transaction_category', 'emi_payment')
                ->whereIn('payment_status', ['paid', 'cleared'])
                ->count();

            $dueMonths = max(0, $emiMonths - $paidEmis);
        }

        return [
            'booking_id' => $plotSale->booking_code ?? 'N/A',

            'customer_id' => $plotSale->customerBooking->customer_code ?? '',
            'customer_name' => $plotSale->customerBooking->primaryDetail->name
                ?? $plotSale->customerBooking->customer_name
                ?? '',

            'plot_sale_id' => $plotSale->id,
            'customer_booking_id' => $plotSale->customer_booking_id,

            'project_name' => $plotSale->project->name ?? '',
            'block_name' => $plotSale->block->block ?? '',
            'plot_number' => $plotSale->plotDetail->plot_number ?? '',
            'plot_area' => $plotSale->plot_area ?? 0,
            'plot_rate' => $plotSale->plot_rate ?? 0,
            'total_plot_cost' => number_format($totalPlotCost, 2),

            'plan_type' => $planType,
            'booking_amount' => number_format((float) ($bookingPayment->booking_amount ?? 0), 2),
            'total_paid' => number_format($totalPaid, 2),
            'remaining_amount' => number_format($remainingAmount, 2),

            'emi_months' => $emiMonths,
            'paid_emis' => $paidEmis,
            'due_months' => $dueMonths,

            'payment_status' => ucfirst($latestPayment->payment_status ?? 'pending'),
            'booking_status' => ucfirst($latestPayment->booking_status ?? 'hold'),
            'payment_mode' => ucfirst($latestPayment->payment_mode ?? 'N/A'),
        ];
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $plotSale = PlotSaleDetail::with([
                'customerBooking.primaryDetail',
            ])->findOrFail($data['plot_sale_detail_id']);

            $oldBooking = $plotSale->customerBooking;

            $newBooking = CustomerBooking::with('primaryDetail')
                ->findOrFail($data['new_customer_booking_id']);

            if ($oldBooking->id == $newBooking->id) {
                throw new \Exception('Same customer ko plot transfer nahi kar sakte.');
            }

            PlotTransferHistory::create([
                'plot_sale_detail_id' => $plotSale->id,

                'old_booking_id' => $oldBooking->id,
                'new_booking_id' => $newBooking->id,

                'old_customer_code' => $oldBooking->customer_code,
                'new_customer_code' => $newBooking->customer_code,

                'old_customer_name' => $oldBooking->primaryDetail->name ?? $oldBooking->customer_name,
                'new_customer_name' => $newBooking->primaryDetail->name ?? $newBooking->customer_name,

                'transfer_charge' => $data['transfer_charge'] ?? 0,
                'transfer_date' => $data['transfer_date'] ?? now()->toDateString(),
                'transfer_reason' => $data['transfer_reason'] ?? null,
                'remark' => $data['remark'] ?? null,

                'created_by' => Auth::id(),
            ]);

            // Existing plot owner update
            $plotSale->update([
                'customer_booking_id' => $newBooking->id,
            ]);

            // Existing payments owner update
            CustomerPayment::where('plot_sale_detail_id', $plotSale->id)
                ->update([
                    'customer_booking_id' => $newBooking->id,
                ]);

            return true;
        });
    }
}
