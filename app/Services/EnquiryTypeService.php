<?php

namespace App\Services;

use App\Models\EnquiryType;

class EnquiryTypeService
{
    public function getAllEnquiryTypes()
    {
        return EnquiryType::latest()->get();
    }

    public function createEnquiryType(array $data)
    {
        return EnquiryType::create($data);
    }

    public function getEnquiryTypeById($id)
    {
        return EnquiryType::findOrFail($id);
    }

    public function updateEnquiryType($id, array $data)
    {
        $enquiryType = $this->getEnquiryTypeById($id);
        $enquiryType->update($data);

        return $enquiryType;
    }

    public function deleteEnquiryType($id)
    {
        $enquiryType = $this->getEnquiryTypeById($id);

        return $enquiryType->delete();
    }
}
