<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\CustomerBooking;
use App\Models\DesignationRank;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class AgentSummaryDetailsReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $levels = DesignationRank::select('id', 'designation', 'commission')->get();
        $associates = Associate::with(['rank', 'children']);
        if ($request->filled('level')) {
            $associates->where('rank_id', $request->level);
        }
        $associates = $associates->get();
        $reports = $associates->map(function ($associate) use ($request) {
            $directBusiness = CustomerBooking::where('associate_id', $associate->id)
                ->whereHas('plotSaleDetail', function ($q) use ($request) {
                    if ($request->filled('from_date')) {
                        $q->whereDate('booking_date', '>=', $request->from_date);
                    }
                    if ($request->filled('to_date')) {
                        $q->whereDate('booking_date', '<=', $request->to_date);
                    }
                })
                ->with('plotSaleDetail')->get()->sum(function ($booking) {
                    return $booking->plotSaleDetail?->total_plot_cost ?? 0;
                });
            $teamIds = $this->getAllChildrenIds($associate);
            $teamBusiness = CustomerBooking::whereIn('associate_id', $teamIds)
                ->whereHas('plotSaleDetail', function ($q) use ($request) {
                    if ($request->filled('from_date')) {
                        $q->whereDate('booking_date', '>=', $request->from_date);
                    }
                    if ($request->filled('to_date')) {
                        $q->whereDate('booking_date', '<=', $request->to_date);
                    }
                })
                ->with('plotSaleDetail')->get()->sum(function ($booking) {
                    return $booking->plotSaleDetail?->total_plot_cost ?? 0;
                });

            return [
                'associate_name' => $associate->associate_name,
                'position' => $associate->rank?->designation ?? 'N/A',
                'direct_business' => $directBusiness,
                'team_business' => $teamBusiness,
                'total' => $directBusiness + $teamBusiness,
            ];
        });

        return view('reports.agent-summary-details-report.index', compact('levels', 'reports'));
    }

    private function getAllChildrenIds($associate)
    {
        $ids = [];
        foreach ($associate->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllChildrenIds($child));
        }

        return $ids;
    }

    public function export(Request $request)
    {
        $associates = Associate::with(['rank', 'children']);
        if ($request->filled('level')) {
            $associates->where('rank_id', $request->level);
        }
        $associates = $associates->get();
        $reports = $associates->map(function ($associate) use ($request) {
            $directBusiness = CustomerBooking::where('associate_id', $associate->id)
                ->whereHas('plotSaleDetail', function ($q) use ($request) {
                    if ($request->filled('from_date')) {
                        $q->whereDate('booking_date', '>=', $request->from_date);
                    }
                    if ($request->filled('to_date')) {
                        $q->whereDate('booking_date', '<=', $request->to_date);
                    }
                })
                ->with('plotSaleDetail')->get()->sum(function ($booking) {
                    return $booking->plotSaleDetail?->total_plot_cost ?? 0;
                });
            $teamIds = $this->getAllChildrenIds($associate);
            $teamBusiness = CustomerBooking::whereIn('associate_id', $teamIds)
                ->whereHas('plotSaleDetail', function ($q) use ($request) {
                    if ($request->filled('from_date')) {
                        $q->whereDate('booking_date', '>=', $request->from_date);
                    }
                    if ($request->filled('to_date')) {
                        $q->whereDate('booking_date', '<=', $request->to_date);
                    }
                })
                ->with('plotSaleDetail')->get()->sum(function ($booking) {
                    return $booking->plotSaleDetail?->total_plot_cost ?? 0;
                });

            return [
                'associate_name' => $associate->associate_name,
                'position' => $associate->rank?->designation ?? 'N/A',
                'direct_business' => $directBusiness,
                'team_business' => $teamBusiness,
                'total' => $directBusiness + $teamBusiness,
            ];
        });

        return $this->excelExportService->export($reports, 'agent-summary-details-report',
            ['Associate Name', 'Position', 'Direct Business', 'Team Business', 'Total'],
            function ($report) {
                return [
                    $report['associate_name'],
                    $report['position'],
                    $report['direct_business'],
                    $report['team_business'],
                    $report['total'],
                ];
            }
        );
    }
}
