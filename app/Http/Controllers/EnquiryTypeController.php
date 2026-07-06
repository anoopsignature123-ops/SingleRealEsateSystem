<?php

namespace App\Http\Controllers;

use App\Services\EnquiryTypeService;
use Illuminate\Http\Request;

class EnquiryTypeController extends Controller
{
    protected $enquiryTypeService;

    public function __construct(EnquiryTypeService $enquiryTypeService)
    {
        $this->enquiryTypeService = $enquiryTypeService;
    }

    public function index()
    {
        $enquiryTypes = $this->enquiryTypeService->getAllEnquiryTypes();

        return view('enquiry_type.index', compact('enquiryTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:enquiry_types,name',
        ]);

        $this->enquiryTypeService->createEnquiryType($validated);

        return redirect()->route('enquiry-type.index')->with('success', 'Enquiry Type created successfully.');
    }

    public function edit($id)
    {
        $enquiryType = $this->enquiryTypeService->getEnquiryTypeById($id);

        return response()->json($enquiryType);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:enquiry_types,name,'.$id,
        ]);

        $this->enquiryTypeService->updateEnquiryType($id, $validated);

        return redirect()->route('enquiry-type.index')->with('success', 'Enquiry Type updated successfully.');
    }

    public function destroy($id)
    {
        $enquiryType = $this->enquiryTypeService->getEnquiryTypeById($id);

        if ($enquiryType->enquiries()->exists()) {
            return redirect()
                ->route('enquiry-type.index')
                ->with('error', 'This lead type is linked with enquiries and cannot be deleted.');
        }

        $this->enquiryTypeService->deleteEnquiryType($id);

        return redirect()->route('enquiry-type.index')->with('success', 'Enquiry Type deleted successfully.');
    }
}
