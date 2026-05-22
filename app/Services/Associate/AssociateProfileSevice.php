<?php

namespace App\Services\Associate;

use App\Models\Associate;
use Illuminate\Support\Facades\Hash;

class AssociateProfileSevice
{
    public function updateProfile(Associate $associate, array $data): Associate
    {
        $associate->update([
            'associate_name' => $data['associate_name'],
            'gender' => $data['gender'],
            'father_name' => $data['father_name'],
            'dob' => $data['dob'],
            'mobile_number' => $data['mobile_number'],
            'email' => $data['email'],
            'pancard_number' => strtoupper($data['pancard_number']),
            'aadhar_number' => $data['aadhar_number'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => ucfirst($data['state']),
        ]);

        $associate->bankDetail()->updateOrCreate(
            ['associate_id' => $associate->id],
            [
                'bank_name' => strtoupper($data['bank_name']),
                'account_number' => $data['account_number'],
                'ifsc_code' => strtoupper($data['ifsc_code']),
                'account_holder_name' => $data['account_holder_name'],
                'nominee_name' => $data['nominee_name'],
                'nominee_relation' => $data['nominee_relation'],
                'nominee_age' => $data['nominee_age'],
            ]
        );

        return $associate;
    }

    public function changePassword($associate, array $data)
    {
        return $associate->update([
            'password' => Hash::make($data['new_password']),
            'plain_password' => $data['new_password'],
        ]);
    }

    public function findAssociateForLetter($id)
    {
        return Associate::with(['sponsor'])->findOrFail($id);
    }
}
