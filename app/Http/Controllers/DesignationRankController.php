<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesignationRankRequest;
use App\Services\DesignationRankService;

class DesignationRankController extends Controller
{
    public function __construct(
        private DesignationRankService $designationRankService
    ) {}

    public function index()
    {
        $designationRanks = $this->designationRankService->getAll();

        return view('designation-ranks.index', compact('designationRanks'));
    }

    public function create()
    {
        return view('designation-ranks.create');
    }

    public function store(DesignationRankRequest $request)
    {
        $this->designationRankService->create($request->validated());

        return redirect()->route('admin.designations.index')->with('success', 'Designation created successfully.');
    }

    public function edit($id)
    {
        $designationRank = $this->designationRankService->find($id);

        return view('designation-ranks.edit', compact('designationRank'));
    }

    public function update(DesignationRankRequest $request, $id)
    {
        $this->designationRankService->update($id, $request->validated());

        return redirect()->route('admin.designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy($id)
    {
        $this->designationRankService->delete($id);

        return back()->with('success', 'Designation deleted successfully.');
    }
}
