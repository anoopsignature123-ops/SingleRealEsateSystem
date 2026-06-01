<?php

namespace App\Http\Controllers;

use App\Http\Requests\FarmerRequest;
use App\Models\Broker;
use App\Services\FarmerService;
use App\Services\LocationService;

class FarmerController extends Controller
{
    protected $farmerService;

    protected $locationService;

    public function __construct(FarmerService $farmerService, LocationService $locationService)
    {
        $this->farmerService = $farmerService;
        $this->locationService = $locationService;
    }

    public function index()
    {
        return view('farmers.index', ['farmers' => $this->farmerService->getFarmers()]);
    }

    public function create()
    {
        $states = $this->locationService->getStates();

        return view('farmers.create', ['brokers' => Broker::latest()->get(), 'states' => $states]);
    }

    public function getCities($stateId)
    {
        return response()->json(
            $this->locationService->getCities($stateId)
        );
    }
    

    public function store(FarmerRequest $request)
    {
        $this->farmerService->createFarmer($request->validated());

        return redirect()->route('farmers.index')->with('success', 'Farmer created successfully.');
    }

    public function show($id)
    {
        return view('farmers.show', ['farmer' => $this->farmerService->findFarmer($id)]);
    }

    public function edit($id)
{
    return view('farmers.edit', [
        'farmer'  => $this->farmerService->findFarmer($id),
        'brokers' => Broker::latest()->get(),
        'states'  => $this->locationService->getStates(),
    ]);
}

    public function update(FarmerRequest $request, $id)
    {
        $this->farmerService->updateFarmer($this->farmerService->findFarmer($id), $request->validated());

        return redirect()->route('farmers.index')->with('success', 'Farmer updated successfully.');
    }

    public function destroy($id)
    {
        $this->farmerService->deleteFarmer($this->farmerService->findFarmer($id));

        return redirect()->route('farmers.index')->with('success', 'Farmer deleted successfully.');
    }
}