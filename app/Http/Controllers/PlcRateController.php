<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlcRateRequest;
use App\Models\PlotType;
use App\Services\PlcRateService;

class PlcRateController extends Controller
{
    public function __construct(
        private PlcRateService $plcRateService
    ) {}

    public function index()
    {
        $plcRates = $this->plcRateService->getAll();

        return view('plc-rates.index', compact('plcRates'));
    }

    public function create()
    {
        $plotTypes = PlotType::all();

        return view('plc-rates.create', compact('plotTypes'));
    }

    public function store(PlcRateRequest $request)
    {
        $this->plcRateService->create($request->validated());

        return redirect()->route('admin.plc-rates.index')->with('success', 'PLC rate created successfully.');
    }

    public function edit($id)
    {
        $plcRate = $this->plcRateService->find($id);

        $plotTypes = PlotType::all();

        return view('plc-rates.edit', compact('plcRate', 'plotTypes'));
    }

    public function update(PlcRateRequest $request, $id)
    {
        $this->plcRateService->update($id, $request->validated());

        return redirect()->route('admin.plc-rates.index')->with('success', 'PLC rate updated successfully.');
    }

    public function destroy($id)
    {
        $this->plcRateService->delete($id);

        return back()->with('success', 'PLC rate deleted successfully.');
    }
}
