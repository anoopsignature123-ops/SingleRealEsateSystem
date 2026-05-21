<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    public function getAll()
    {
        return Company::latest()->get();
    }

    public function create(array $data)
    {
        // Agar nayi company active banayi ja rahi hai, baaki sabko inactive karo
        if (isset($data['status']) && $data['status'] == 1) {
            Company::query()->update(['status' => 0]);
        } else {
            $data['status'] = 0;
        }

        $data['logo'] = uploadFile($data['logo'] ?? null, 'companies');

        return Company::create($data);
    }

    public function find($id)
    {
        return Company::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $company = Company::findOrFail($id);

        if (isset($data['logo'])) {
            deleteFile($company->logo);
            $data['logo'] = uploadFile($data['logo'], 'companies');
        }

        // Agar is company ko active kiya ja raha hai, baaki sabko inactive karo
        if (isset($data['status']) && $data['status'] == 1) {
            Company::where('id', '!=', $id)->update(['status' => 0]);
            $status = 1;
        } else {
            // Agar ek hi company active bachi ho, toh use inactive nahi karne dena chahiye system breaks se bachne ke liye
            $status = isset($data['status']) ? $data['status'] : $company->status;
        }

        $company->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'website_link' => $data['website_link'],
            'contact_no' => $data['contact_no'],
            'address' => $data['address'],
            'status' => $status,
            'logo' => $data['logo'] ?? $company->logo,
        ]);

        return $company;
    }

    public function updateStatus($id)
    {
        // Pura common logic: Is particular company ko 1 karo, baki sabko 0 karo
        Company::where('id', '!=', $id)->update(['status' => 0]);

        $company = Company::findOrFail($id);
        $company->update(['status' => 1]);

        return $company;
    }

    public function delete($id)
    {
        $company = Company::findOrFail($id);
        deleteFile($company->logo);

        return $company->delete();
    }
}
