<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\CustomerBooking;
use App\Models\Project;
use Illuminate\Http\Request;

class CustomerLedgerReportController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::select('id', 'name')->get();
        $ledger = null;
        $query = CustomerBooking::with([
            'primaryDetail.correspondenceDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
            'payment',
            'payments',
        ]);
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('booking_id')) {
            $query->where('booking_code', $request->booking_id);
        }
        if ($request->filled('project_id')) {
            $query->whereHas('plotSaleDetail', function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }
        if ($request->filled('block_id')) {
            $query->whereHas('plotSaleDetail', function ($q) use ($request) {
                $q->where('block_id', $request->block_id);
            });
        }
        if ($request->filled('plot_id')) {
            $query->whereHas('plotSaleDetail.plotDetail', function ($q) use ($request) {
                $q->where('id', $request->plot_id);
            });
        }
        $ledger = $query->first();

        return view('reports.customer-ledger-report.index', compact('projects', 'ledger')
        );
    }

    public function getBlocks($projectId)
    {
        $blocks = Block::whereIn('id', CustomerBooking::whereHas('plotSaleDetail', function ($q) use ($projectId) {
            $q->where('project_id', $projectId);
        })->with('plotSaleDetail')->get()->pluck('plotSaleDetail.block_id')->unique()
        )->select('id', 'block')->get();

        return response()->json($blocks);
    }

    // Block -> Customers
    public function getCustomers($projectId, $blockId)
    {
        $customers = CustomerBooking::with('primaryDetail')
            ->whereHas('plotSaleDetail', function ($q) use ($projectId, $blockId) {
                $q->where('project_id', $projectId)->where('block_id', $blockId);
            })
            ->get();

        return response()->json($customers);
    }

    public function getPlots($customerId)
    {
        $plots = CustomerBooking::with('plotSaleDetail.plotDetail')
            ->where('customer_id', $customerId)->get();

        return response()->json($plots);
    }

    public function getBooking($plotId, $customerId)
    {
        $booking = CustomerBooking::where('customer_id', $customerId)
            ->whereHas('plotSaleDetail', function ($q) use ($plotId) {
                $q->where('plot_detail_id', $plotId);
            })->first();

        return response()->json($booking);
    }
}
