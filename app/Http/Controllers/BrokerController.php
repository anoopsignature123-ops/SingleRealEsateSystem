<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrokerRequest;
use App\Models\Broker;
use App\Services\BrokerService;
use App\Services\LocationService;

class BrokerController extends Controller
{
    protected $brokerService;

    protected $locationService;

    public function __construct(BrokerService $brokerService, LocationService $locationService)
    {
        $this->brokerService = $brokerService;
        $this->locationService = $locationService;
    }

    public function index()
    {
        $brokers = $this->brokerService->getBrokers();

        return view('brokers.index', compact('brokers'));
    }

    public function create()
    {
        return view('farmers.create', [
            'brokers' => Broker::latest()->get(),
            'states' => $this->locationService->getStates(),
        ]);
    }

    public function store(BrokerRequest $request)
    {

        $this->brokerService->createBroker($request->validated());

        return redirect()
            ->route('brokers.index')
            ->with('success', 'Broker created successfully.');
    }

    public function show($id)
    {
        $broker = $this->brokerService->findBroker($id);

        return view('brokers.show', compact('broker'));
    }

    public function edit($id)
    {
        return view('brokers.edit', [
            'broker' => $this->brokerService->findBroker($id),
              'brokers' => $this->brokerService->getBrokers(),
        'states'  => $this->locationService->getStates(),
        ]);
    }

    public function update(BrokerRequest $request, $id)
    {
        $broker = $this->brokerService->findBroker($id);

        $this->brokerService->updateBroker(
            $broker,
            $request->validated()
        );

        return redirect()
            ->route('brokers.index')
            ->with('success', 'Broker updated successfully.');
    }

    public function destroy($id)
    {
        $broker = $this->brokerService->findBroker($id);

        $this->brokerService->deleteBroker($broker);

        return redirect()
            ->route('brokers.index')
            ->with('success', 'Broker deleted successfully.');
    }
}