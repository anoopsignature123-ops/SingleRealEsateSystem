<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevelopmentRequest;
use App\Services\DevelopmentService;

class DevelopmentController extends Controller
{
    public function __construct(private DevelopmentService $developmentService) {}

    public function index()
    {
        $developments = $this->developmentService->getAll();

        return view('developments.index', compact('developments'));
    }

    public function create()
    {
        return view('developments.create');
    }

    public function store(DevelopmentRequest $request)
    {
        $this->developmentService->create($request->validated());

        return redirect()->route('admin.developments.index')->with('success', 'Development created successfully.');
    }

    public function edit($id)
    {
        $development = $this->developmentService->find($id);

        return view('developments.edit', compact('development'));
    }

    public function update(DevelopmentRequest $request, $id)
    {
        $this->developmentService->update($id, $request->validated());

        return redirect()->route('admin.developments.index')->with('success', 'Development updated successfully.');
    }

    public function destroy($id)
    {
        $this->developmentService->delete($id);

        return back()->with('success', 'Development deleted successfully.');
    }
}
