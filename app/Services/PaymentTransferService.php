<?php

namespace App\Services;

use App\Models\Block;
use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Models\PaymentTransferHistory;
use App\Models\PlotDetail;
use App\Models\PlotSaleDetail;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentTransferService
{
    public function index()
    {
        $projects = Project::select('id', 'name')
            ->orderBy('name')
            ->get();

        $histories = PaymentTransferHistory::with([
            'customerPayment',
            'oldCustomerBooking.primaryDetail',
            'newCustomerBooking.primaryDetail',
            'oldPlotSaleDetail.project',
            'oldPlotSaleDetail.block',
            'oldPlotSaleDetail.plotDetail',
            'newPlotSaleDetail.project',
            'newPlotSaleDetail.block',
            'newPlotSaleDetail.plotDetail',
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
            ->whereHas('plotSaleDetail.payments')
            ->select('id', 'plot_number')
            ->orderBy('plot_number')
            ->get();
    }

    public function getPayments($plotId)
    {
        $plotSale = PlotSaleDetail::with([
            'project',
            'block',
            'plotDetail',
            'customerBooking.primaryDetail',
            'payments',
        ])
            ->where('plot_detail_id', $plotId)
            ->first();

        if (! $plotSale) {
            return [
                'status' => false,
                'message' => 'Plot sale details not found.',
            ];
        }

        $payments = $plotSale->payments()
            ->orderBy('id')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'receipt_number' => $payment->receipt_number ?? '-',
                    'date' => $payment->created_at
                        ? $payment->created_at->format('d-m-Y')
                        : '-',
                    'plan_type' => $payment->plan_type ?? '-',
                    'transaction_category' => $payment->transaction_category ?? '-',
                    'payment_mode' => strtoupper(str_replace('_', '/', $payment->payment_mode ?? '-')),
                    'booking_status' => ucfirst($payment->booking_status ?? '-'),
                    'payment_status' => ucfirst($payment->payment_status ?? '-'),
                    'paid_amount' => number_format((float) ($payment->paid_amount ?? 0), 2),
                ];
            });

        return [
            'status' => true,

            'plot_sale_id' => $plotSale->id,
            'booking_code' => $plotSale->booking_code ?? 'N/A',

            'customer_booking_id' => $plotSale->customer_booking_id,
            'customer_code' => $plotSale->customerBooking->customer_code ?? '',
            'customer_name' => $plotSale->customerBooking->primaryDetail->name
                ?? $plotSale->customerBooking->customer_name
                ?? '',

            'project_name' => $plotSale->project->name ?? '',
            'block_name' => $plotSale->block->block ?? '',
            'plot_number' => $plotSale->plotDetail->plot_number ?? '',

            'payments' => $payments,
        ];
    }

    public function getCustomers()
    {
        return CustomerBooking::with('primaryDetail')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('customer_code')
            ->orderBy('customer_code')
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->customer_code . ' - ' . (
                        $customer->primaryDetail->name
                        ?? $customer->customer_name
                        ?? 'N/A'
                    ),
                ];
            });
    }

    public function getCustomerPlots($customerBookingId)
    {
        return PlotSaleDetail::with([
            'project',
            'block',
            'plotDetail',
        ])
            ->where('customer_booking_id', $customerBookingId)
            ->whereNotNull('booking_code')
            ->orderByDesc('id')
            ->get()
            ->map(function ($plotSale) {
                return [
                    'id' => $plotSale->id,
                    'name' => ($plotSale->booking_code ?? 'N/A')
                        . ' | '
                        . ($plotSale->project->name ?? 'Project')
                        . ' / '
                        . ($plotSale->block->block ?? 'Block')
                        . ' / Plot '
                        . ($plotSale->plotDetail->plot_number ?? 'N/A'),
                ];
            });
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $newBooking = CustomerBooking::with('primaryDetail')
                ->findOrFail($data['new_customer_booking_id']);

            $newPlotSale = PlotSaleDetail::with([
                'project',
                'block',
                'plotDetail',
            ])->findOrFail($data['new_plot_sale_detail_id']);

            if ($newPlotSale->customer_booking_id != $newBooking->id) {
                throw new \Exception('Selected plot does not belong to selected customer.');
            }

            $payments = CustomerPayment::with([
                'customerBooking.primaryDetail',
                'plotSaleDetail',
            ])
                ->whereIn('id', $data['payment_ids'])
                ->get();

            foreach ($payments as $payment) {

                $oldBooking = $payment->customerBooking;
                $oldPlotSale = $payment->plotSaleDetail;

                if (! $oldBooking || ! $oldPlotSale) {
                    throw new \Exception('Invalid old payment record found.');
                }

                if (
                    $payment->customer_booking_id == $newBooking->id &&
                    $payment->plot_sale_detail_id == $newPlotSale->id
                ) {
                    continue;
                }

                PaymentTransferHistory::create([
                    'customer_payment_id' => $payment->id,

                    'old_customer_booking_id' => $oldBooking->id,
                    'new_customer_booking_id' => $newBooking->id,

                    'old_plot_sale_detail_id' => $oldPlotSale->id,
                    'new_plot_sale_detail_id' => $newPlotSale->id,

                    'old_booking_code' => $oldPlotSale->booking_code,
                    'new_booking_code' => $newPlotSale->booking_code,

                    'old_customer_code' => $oldBooking->customer_code,
                    'new_customer_code' => $newBooking->customer_code,

                    'old_customer_name' => $oldBooking->primaryDetail->name ?? $oldBooking->customer_name,
                    'new_customer_name' => $newBooking->primaryDetail->name ?? $newBooking->customer_name,

                    'transfer_amount' => $payment->paid_amount ?? $payment->booking_amount ?? 0,
                    'transfer_date' => $data['transfer_date'] ?? now()->toDateString(),
                    'transfer_reason' => $data['transfer_reason'] ?? null,
                    'remark' => $data['remark'] ?? null,

                    'created_by' => Auth::id(),
                ]);

                $payment->update([
                    'customer_booking_id' => $newBooking->id,
                    'plot_sale_detail_id' => $newPlotSale->id,
                ]);
            }

            return true;
        });
    }
}