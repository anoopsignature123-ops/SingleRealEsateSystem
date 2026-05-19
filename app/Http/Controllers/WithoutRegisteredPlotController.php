<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\CustomerBooking;
use App\Models\Project;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class WithoutRegisteredPlotController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function getCustomerDetails($id)
    {
        $customer = CustomerBooking::with('primaryDetail')->find($id);

        return response()->json(['name' => $customer?->primaryDetail?->name]);
    }

    public function index(Request $request)
    {
        $query = CustomerBooking::with([
            'primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ])->whereDoesntHave('plotRegistry');
        if ($request->customer_id) {
            $query->where('id', $request->customer_id);
        }
        if ($request->project_id) {
            $query->whereHas('plotSaleDetail',
                function ($q) use ($request) {
                    $q->where('project_id', $request->project_id);
                }
            );
        }
        if ($request->block_id) {
            $query->whereHas('plotSaleDetail',
                function ($q) use ($request) {
                    $q->where('block_id', $request->block_id
                    );
                }
            );
        }
        $bookings = $query->latest()->get();
        $customerIds = CustomerBooking::get();
        $projects = Project::all();
        $blocks = Block::all();

        return view('reports.without-registered-plot.index', compact('bookings', 'customerIds', 'projects', 'blocks'));
    }

    public function export(Request $request)
    {
        $query = CustomerBooking::with([
            'primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.plotDetail',
        ])->whereDoesntHave('plotRegistry');
        if ($request->customer_id) {
            $query->where('id', $request->customer_id);
        }
        $bookings = $query->latest()->get();

        return $this->excelExportService->export($bookings, 'without-registered-plot-report',
            [
                'Booking ID',
                'Customer Name',
                'Project Name',
                'Plot No',
                'Total Cost',
            ],
            function ($booking) {
                return [
                    $booking->booking_code ?? 'N/A',
                    $booking->primaryDetail?->name ?? 'N/A',
                    $booking->plotSaleDetail?->project?->name ?? 'N/A',
                    $booking->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    $booking->plotSaleDetail?->total_plot_cost ?? 0,
                ];
            }
        );
    }
}
