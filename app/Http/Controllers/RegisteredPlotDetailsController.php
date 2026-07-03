<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\CustomerBooking;
use App\Models\PlotRegistry;
use App\Models\Project;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class RegisteredPlotDetailsController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function getProjectBlocks($id)
    {
        return response()->json(
            Block::where('project_id', $id)->get()
        );
    }

    public function index(Request $request)
    {
        $registries = $this->buildQuery($request)->latest()->get();

        $summary = [
            'total_records' => $registries->count(),
            'total_cost' => $registries->sum(fn($item) => (float) ($item->plotDetail?->plotSaleDetail?->total_plot_cost ?? 0)),
            'total_projects' => $registries->pluck('project_id')->filter()->unique()->count(),
            'total_blocks' => $registries->pluck('block_id')->filter()->unique()->count(),
        ];

        $customerIds = CustomerBooking::with('primaryDetail')->get();
        $projects = Project::all();
        $selectedBlockId = $request->block_id;

        return view(
            'reports.registered-plot-details.index',
            compact('registries', 'customerIds', 'projects', 'summary', 'selectedBlockId')
        );
    }

    public function export(Request $request)
    {
        $registries = $this->buildQuery($request)->latest()->get();

        return $this->excelExportService->export(
            $registries,
            'registered-plot-report',
            [
                'Booking ID',
                'Customer ID',
                'Customer Name',
                'Project Name',
                'Block',
                'Plot No',
                'Gata No',
                'Seller Name',
                'Registry No',
                'Registry Date',
                'Total Cost',
            ],
            function ($item) {
                return [
                    $item->customerBooking?->booking_code ?? 'N/A',
                    $item->customerBooking?->customer_code ?? 'N/A',
                    $item->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $item->project?->name ?? 'N/A',
                    $item->block?->block ?? 'N/A',
                    $item->plotDetail?->plot_number ?? 'N/A',
                    $item->gata_number ?? 'N/A',
                    $item->seller_name ?? 'N/A',
                    $item->register_no ?? 'N/A',
                    $item->register_date ? date('d-m-Y', strtotime($item->register_date)) : 'N/A',
                    number_format($item->plotDetail?->plotSaleDetail?->total_plot_cost ?? 0, 2, '.', ''),
                ];
            }
        );
    }

    private function buildQuery(Request $request)
    {
        $query = PlotRegistry::with([
            'customerBooking.primaryDetail',
            'project',
            'block',
            'plotDetail.plotSaleDetail',
        ]);

        if ($request->filled('customer_id')) {
            $query->where('customer_booking_id', $request->customer_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('block_id')) {
            $query->where('block_id', $request->block_id);
        }

        return $query;
    }
}