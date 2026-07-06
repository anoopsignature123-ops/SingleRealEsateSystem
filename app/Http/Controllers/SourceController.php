<?php

namespace App\Http\Controllers;

use App\Http\Requests\SourceRequest;
use App\Services\SourceService;

class SourceController extends Controller
{
    protected $service;

    public function __construct(SourceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $sources = $this->service->getAll();

        return view('source.index', compact('sources'));
    }

    public function store(SourceRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('source.index')
            ->with('success', 'Source Created Successfully');
    }

    public function edit($id)
    {
        $source = $this->service->findById($id);

        return response()->json($source);
    }

    public function update(SourceRequest $request, $id)
    {
        $this->service->update($request->validated(), $id);

        return redirect()
            ->route('source.index')
            ->with('success', 'Source Updated Successfully');
    }

    public function destroy($id)
    {
        $source = $this->service->findById($id);

        if ($source->enquiries()->exists()) {
            return redirect()
                ->route('source.index')
                ->with('error', 'This source is linked with enquiries and cannot be deleted.');
        }

        $this->service->delete($id);

        return redirect()
            ->route('source.index')
            ->with('success', 'Source Deleted Successfully');
    }
}
