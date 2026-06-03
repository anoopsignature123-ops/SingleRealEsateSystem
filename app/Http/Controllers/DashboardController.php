<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Models\PlotDetail;
use App\Models\PlotRegistry;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'projectCount' => Project::count(),
            'totalPlot' => PlotDetail::count(),
            'totalCustomer' => CustomerBooking::whereNotNull('customer_code')->count(),
            'totalAssociate' => Associate::count(),
            'plotStats' => $this->getPlotStats(),
            'visitorsData' => $this->getVisitorsData(),
            'monthlyDues' => $this->getMonthlyDues(),
            'totalOutstanding' => $this->calculateOutstanding(),
            'totalOverdue' => $this->calculateOverdue(),

            'confirmedPayment' => CustomerPayment::sum('booking_amount'),
            'pendingPayment' => CustomerPayment::sum('due_amount'),
        ];

        return view('dashboard', array_merge($data, $data['plotStats']));
    }

    private function getPlotStats()
    {
        $stats = PlotDetail::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'booked' => $stats['booked'] ?? 0,
            'hold' => $stats['hold'] ?? 0,
            'registry' => $stats['registry'] ?? PlotRegistry::count(),
            'available' => $stats['available'] ?? 0,
        ];
    }

    private function getMonthlyDues()
    {
        return CustomerPayment::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ])
            ->where('plan_type', 'emi_plan')
            ->where('payment_status', 'pending')
            ->where('due_amount', '>', 0)
            ->whereMonth('emi_date', now()->month)
            ->whereYear('emi_date', now()->year)
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('customer_payments')
                    ->where('plan_type', 'emi_plan')
                    ->whereNotNull('plot_sale_detail_id')
                    ->groupBy('customer_booking_id', 'plot_sale_detail_id');
            })
            ->latest()
            ->get();
    }

    private function calculateOutstanding()
    {
        return CustomerPayment::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('customer_payments')
                ->whereNotNull('plot_sale_detail_id')
                ->groupBy('customer_booking_id', 'plot_sale_detail_id');
        })
            ->where('payment_status', 'pending')
            ->where('due_amount', '>', 0)
            ->sum('due_amount');
    }

    private function calculateOverdue()
    {
        return CustomerPayment::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('customer_payments')
                ->whereNotNull('plot_sale_detail_id')
                ->groupBy('customer_booking_id', 'plot_sale_detail_id');
        })
            ->where('plan_type', 'emi_plan')
            ->where('payment_status', 'pending')
            ->where('due_amount', '>', 0)
            ->whereNotNull('emi_date')
            ->whereDate('emi_date', '<', now())
            ->sum('due_amount');
    }

    private function getVisitorsData()
    {
        return [
            'labels' => ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'registered' => [3500, 2500, 5000, 3000, 2800, 3200, 2600, 4534, 2700, 3100],
            'guests' => [4800, 4200, 7000, 6200, 5800, 6500, 5000, 7675, 6000, 5500],
        ];
    }
}