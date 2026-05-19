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

    public function __construct(
        ExcelExportService $excelExportService
    ) {
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
        $query = PlotRegistry::with([
            'customerBooking.primaryDetail',
            'project',
            'block',
            'plotDetail',
        ]);
        if ($request->customer_id) {
            $query->where('customer_booking_id', $request->customer_id);
        }
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->block_id) {
            $query->where('block_id', $request->block_id);
        }
        $registries = $query->latest()->get();
        $customerIds = CustomerBooking::all();
        $projects = Project::all();

        return view('reports.registered-plot-details.index', compact('registries', 'customerIds', 'projects'));
    }

    public function export(Request $request)
    {
        $query = PlotRegistry::with([
            'customerBooking.primaryDetail',
            'project',
            'block',
            'plotDetail',
        ]);
        if ($request->customer_id) {
            $query->where('customer_booking_id', $request->customer_id);
        }
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->block_id) {
            $query->where('block_id', $request->block_id);
        }
        $registries = $query->latest()->get();

        return $this->excelExportService->export($registries, 'registered-plot-report',
            [
                'Booking ID',
                'Customer Name',
                'Project Name',
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
                    $item->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $item->project?->name ?? 'N/A',
                    $item->plotDetail?->plot_number ?? 'N/A',
                    $item->gata_number ?? 'N/A',
                    $item->seller_name ?? 'N/A',
                    $item->register_no ?? 'N/A',
                    $item->register_date ? date('d-m-Y', strtotime($item->register_date)) : 'N/A',
                    $item->plotDetail?->plotSaleDetail?->total_plot_cost ?? 0,
                ];
            }
        );
    }
}
