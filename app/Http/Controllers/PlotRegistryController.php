<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlotRegistryRequest;
use App\Services\PlotRegistryService;

class PlotRegistryController extends Controller
{
    public function __construct(
        protected PlotRegistryService $plotRegistryService
    ) {}

    public function index()
    {
        $data = $this->plotRegistryService->index();

        return view('plot-registry.index', $data);
    }

    public function getBlocks($projectId)
    {
        return response()->json(
            $this->plotRegistryService->getBlocks($projectId)
        );
    }

    public function getPlots($blockId)
    {
        return response()->json(
            $this->plotRegistryService->getPlots($blockId)
        );
    }

    public function getBookingData($plotId)
    {
        return response()->json(
            $this->plotRegistryService->getBookingData($plotId)
        );
    }

    public function store(PlotRegistryRequest $request)
    {
        $this->plotRegistryService->create($request->validated());

        return redirect()
            ->back()
            ->with('success', 'Plot registry created successfully.');
    }
}