<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEmiDateRequest;
use App\Services\UpdateEmiDateService;

class UpdateEmiDateController extends Controller
{
    public function __construct(
        protected UpdateEmiDateService $service
    ) {}

    public function index()
    {
        $payments = $this->service->getEmiPayments();

        return view('payment.update-emi-date.index', compact('payments'));
    }

    public function store(UpdateEmiDateRequest $request)
    {
        $this->service->store($request->validated());

        return back()->with('success', 'EMI date updated successfully.');
    }
}