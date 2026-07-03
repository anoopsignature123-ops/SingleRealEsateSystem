<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\CustomerBooking;
use App\Models\DesignationRank;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

        $reports = $this->buildReports($request);

        $summary = [
            'total_agents' => $reports->count(),
            'total_direct_business' => $reports->sum('direct_business'),
            'total_team_business' => $reports->sum('team_business'),
            'grand_total' => $reports->sum('total'),
        ];

        return view(
            'reports.agent-summary-details-report.index',
            compact('levels', 'reports', 'summary')
        );
    }

    public function export(Request $request)
    {
        $reports = $this->buildReports($request);

        return $this->excelExportService->export(
            $reports,
            'agent-summary-details-report',
            [
                'Associate ID',
                'Associate Name',
                'Position',
                'Commission %',
                'Direct Team',
                'Direct Business',
                'Team Business',
                'Total Business',
            ],
            function ($report) {
                return [
                    $report['associate_code'],
                    $report['associate_name'],
                    $report['position'],
                    $report['commission'],
                    $report['direct_team_count'],
                    number_format($report['direct_business'], 2, '.', ''),
                    number_format($report['team_business'], 2, '.', ''),
                    number_format($report['total'], 2, '.', ''),
                ];
            }
        );
    }

    private function buildReports(Request $request): Collection
    {
        $associatesQuery = Associate::with(['rank', 'children.children']);

        if ($request->filled('level')) {
            $associatesQuery->where('rank_id', $request->level);
        }

        return $associatesQuery->get()->map(function ($associate) use ($request) {
            $directBusiness = $this->calculateBusiness([$associate->id], $request);

            $teamIds = $this->getAllChildrenIds($associate);
            $teamBusiness = !empty($teamIds)
                ? $this->calculateBusiness($teamIds, $request)
                : 0;

            return [
                'associate_code' => $associate->associate_code ?? $associate->associate_id ?? 'N/A',
                'associate_name' => $associate->associate_name ?? 'N/A',
                'position' => $associate->rank?->designation ?? 'N/A',
                'commission' => $associate->rank?->commission ?? 0,
                'direct_team_count' => $associate->children->count(),
                'team_count' => count($teamIds),
                'direct_business' => $directBusiness,
                'team_business' => $teamBusiness,
                'total' => $directBusiness + $teamBusiness,
            ];
        })->values();
    }

    private function calculateBusiness(array $associateIds, Request $request): float
    {
        if (empty($associateIds)) {
            return 0;
        }

        return CustomerBooking::whereIn('associate_id', $associateIds)
            ->whereHas('plotSaleDetails', function ($q) use ($request) {
                if ($request->filled('from_date')) {
                    $q->whereDate('booking_date', '>=', $request->from_date);
                }

                if ($request->filled('to_date')) {
                    $q->whereDate('booking_date', '<=', $request->to_date);
                }
            })
            ->with('plotSaleDetails')
            ->get()
            ->sum(function ($booking) {
                return $booking->plotSaleDetails->sum('total_plot_cost');
            });
    }

    private function getAllChildrenIds($associate): array
    {
        $ids = [];

        foreach ($associate->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllChildrenIds($child));
        }

        return $ids;
    }
}