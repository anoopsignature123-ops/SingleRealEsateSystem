<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\CustomerBooking;
use App\Models\PlotDetail;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $projectCount = Project::count();
        $totalPlot = PlotDetail::count();
        $totalCustomer = CustomerBooking::count();
        $totalAssociate = Associate::count();

        $query = PlotDetail::query();

        $totalBookedPlot = (clone $query)->where('status', 'booked')->count();

        $totalHoldPlot = (clone $query)->where('status', 'hold')->count();

        $totalRegistryPlot = (clone $query)->where('status', 'registry')->count();

        $totalAvailablePlot = (clone $query)->where('status', 'available')->count();

        return view('dashboard', compact(
            'projectCount',
            'totalPlot',
            'totalCustomer',
            'totalAssociate',
            'totalBookedPlot',
            'totalHoldPlot',
            'totalRegistryPlot',
            'totalAvailablePlot'
        ));
    }
}
