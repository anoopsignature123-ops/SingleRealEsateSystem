<?php

namespace App\Services;

use App\Models\CancelBooking;
use App\Models\CustomerBooking;
use App\Models\PlotDetail;
use App\Models\PlotSaleDetail;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class CancelBookingService
{
    public function index()
    {
        $projects = Project::select('id', 'name')
            ->orderBy('name')
            ->get();

        $plotSales = PlotSaleDetail::with([
            'project',
            'block',
            'plotDetail',
            'customerBooking.primaryDetail',
            'payments',
        ])
            ->whereNotNull('booking_code')
            ->whereHas('customerBooking', function ($query) {
                $query->where('status', '!=', 'cancelled');
            })
            ->whereHas('plotDetail', function ($query) {
                $query->whereIn('status', ['booked', 'hold']);
            })
            ->latest()
            ->get();

        $cancelHistories = CancelBooking::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ])
            ->latest()
            ->get();

        return compact('projects', 'plotSales', 'cancelHistories');
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $plotSale = PlotSaleDetail::with([
                'customerBooking',
                'payments',
                'plotDetail',
            ])->findOrFail($data['plot_sale_detail_id']);

            $booking = $plotSale->customerBooking;

            if (! $booking) {
                abort(404, 'Customer booking not found.');
            }

            CancelBooking::create($data);

            if ($plotSale->plot_detail_id) {
                PlotDetail::where('id', $plotSale->plot_detail_id)
                    ->update([
                        'status' => 'available',
                    ]);
            }

            /*
             * Important:
             * CustomerBooking ko direct cancelled mat karo,
             * kyunki same customer ke multiple plots ho sakte hain.
             * Agar customer ke saare plot inactive/cancel ho gaye tabhi cancelled karo.
             */
            $activePlotCount = PlotSaleDetail::where('customer_booking_id', $booking->id)
                ->where('id', '!=', $plotSale->id)
                ->whereNotNull('booking_code')
                ->whereHas('plotDetail', function ($query) {
                    $query->whereIn('status', ['booked', 'hold']);
                })
                ->count();

            if ($activePlotCount <= 0) {
                $booking->update([
                    'status' => 'cancelled',
                ]);
            }

            return true;
        });
    }
}
