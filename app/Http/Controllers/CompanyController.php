<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Services\CompanyService;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        $companies = $this->companyService->getAll();

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(CompanyRequest $request)
    {

        $this->companyService->create($request->all());

        return redirect()->route('admin.company.index')->with('success', 'Company created successfully');
    }

    public function edit($id)
    {
        $company = $this->companyService->find($id);

        return view('companies.edit', compact('company'));
    }

    public function update(CompanyRequest $request, $id)
    {

        $this->companyService->update($id, $request->all());

        return redirect()->route('admin.company.index')->with('success', 'Company updated successfully');
    }

    public function destroy($id)
    {
        $this->companyService->delete($id);

        return redirect()->route('admin.company.index')->with('success', 'Company deleted successfully');
    }
}
