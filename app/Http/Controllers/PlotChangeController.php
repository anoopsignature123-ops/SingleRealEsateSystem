<?php

namespace App\Http\Controllers;

use App\Services\PlotChangeService;
use Illuminate\Http\Request;

class PlotChangeController extends Controller
{
    public function __construct(
        protected PlotChangeService $plotChangeService
    ) {}

    public function index()
    {
        $data = $this->plotChangeService->index();

        return view('plot_change.index', $data);
    }

    public function getBlocks($projectId)
    {
        return response()->json(
            $this->plotChangeService->getBlocks($projectId)
        );
    }

    public function getBookedPlots($blockId)
    {
        return response()->json(
            $this->plotChangeService->getBookedPlots($blockId)
        );
    }

    public function getAvailablePlots($blockId)
    {
        return response()->json(
            $this->plotChangeService->getAvailablePlots($blockId)
        );
    }

    public function getBookingData($plotId)
    {
        return response()->json(
            $this->plotChangeService->getBookingData($plotId)
        );
    }

    public function getNewPlotData($plotId)
    {
        return response()->json(
            $this->plotChangeService->getNewPlotData($plotId)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plot_sale_detail_id' => 'required|exists:plot_sale_details,id',
            'new_project_id' => 'required|exists:projects,id',
            'new_block_id' => 'required|exists:blocks,id',
            'new_plot_detail_id' => 'required|exists:plot_details,id',
            'change_date' => 'nullable|date',
            'change_reason' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        $this->plotChangeService->store($data);

        return response()->json([
            'status' => true,
            'message' => 'Plot changed successfully.',
        ]);
    }
}