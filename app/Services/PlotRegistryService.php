<?php

namespace App\Services;

use App\Models\Block;
use App\Models\PlotDetail;
use App\Models\PlotRegistry;
use App\Models\PlotSaleDetail;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class PlotRegistryService
{
    public function index()
    {
        $projects = Project::select('id', 'name')
            ->orderBy('name')
            ->get();

        $registries = PlotRegistry::with([
            'project',
            'block',
            'plotDetail',
            'customerBooking.primaryDetail',
        ])
            ->latest()
            ->get();

        return compact('projects', 'registries');
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
            ->whereHas('plotSaleDetail.payments', function ($query) {
                $query->whereIn('booking_status', ['booked'])
                    ->whereIn('payment_status', ['cleared', 'pending']);
            })
            ->whereDoesntHave('plotRegistry')
            ->select('id', 'plot_number')
            ->orderBy('plot_number')
            ->get();
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
            return [
                'status' => false,
                'message' => 'Booking data not found.',
            ];
        }

        $payments = $plotSale->payments;

        $totalPaid = (float) $payments
            ->where('booking_status', 'booked')
            ->sum('paid_amount');

        $totalCost = (float) ($plotSale->total_plot_cost ?? 0);
        $dueAmount = max(0, $totalCost - $totalPaid);

        $paymentHistory = $payments->map(function ($payment) {
            return [
                'receipt_no' => $payment->receipt_number ?? '-',
                'amount' => number_format((float) ($payment->paid_amount ?? 0), 2),
                'date' => $payment->created_at ? $payment->created_at->format('d-m-Y') : '-',
                'mode' => strtoupper(str_replace('_', ' / ', $payment->payment_mode ?? '-')),
                'status' => ucfirst($payment->payment_status ?? '-'),
                'category' => ucfirst(str_replace('_', ' ', $payment->transaction_category ?? '-')),
                'cheque_no' => $payment->cheque_number ?? '-',
            ];
        })->values();

        return [
            'status' => true,

            'booking_db_id' => $plotSale->customer_booking_id,
            'plot_sale_detail_id' => $plotSale->id,

            'booking_id' => $plotSale->booking_code ?? $plotSale->customerBooking?->booking_code ?? '',
            'customer_id' => $plotSale->customerBooking?->customer_code ?? '',
            'customer_name' => $plotSale->customerBooking?->primaryDetail?->name
                ?? $plotSale->customerBooking?->customer_name
                ?? '',

            'project_name' => $plotSale->project?->name ?? '',
            'block_name' => $plotSale->block?->block ?? '',
            'plot_number' => $plotSale->plotDetail?->plot_number ?? '',

            'total_cost' => number_format($totalCost, 2),
            'total_paid' => number_format($totalPaid, 2),
            'due_amount' => number_format($dueAmount, 2),

            'payment_history' => $paymentHistory,
        ];
    }

    public function create(array $data): PlotRegistry
    {
        return DB::transaction(function () use ($data) {

            $registry = PlotRegistry::create([
                'project_id' => $data['project_id'],
                'block_id' => $data['block_id'],
                'plot_detail_id' => $data['plot_detail_id'],
                'customer_booking_id' => $data['customer_booking_id'],

                'gata_number' => $data['gata_number'],
                'seller_name' => $data['seller_name'],
                'register_no' => $data['register_no'],
                'register_date' => $data['register_date'],
            ]);

            PlotDetail::where('id', $data['plot_detail_id'])
                ->update([
                    'status' => 'registry',
                ]);

            return $registry;
        });
    }
}