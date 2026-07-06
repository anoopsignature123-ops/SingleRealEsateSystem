<?php

namespace App\Services;

use App\Models\Enquiry;

class EnquiryService
{
    public function getAll()
    {
        return Enquiry::with(['associate', 'source', 'enquiryType'])->latest()->get();
    }

    public function store($data)
    {
        return Enquiry::create($data);
    }

    public function findById($id)
    {
        return Enquiry::with(['associate', 'source', 'enquiryType'])->findOrFail($id);
    }

    public function update($data, $id)
    {
        $enquiry = Enquiry::findOrFail($id);
        $enquiry->update($data);

        return $enquiry;
    }

    public function delete($id)
    {
        $enquiry = Enquiry::findOrFail($id);

        return $enquiry->delete();
    }
}
