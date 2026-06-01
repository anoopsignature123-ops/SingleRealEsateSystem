<?php

namespace App\Services;

use App\Models\Broker;
use App\Models\BrokerBankDetail;

class BrokerService
{
    public function getBrokers()
    {
         return Broker::with('bankDetail')->latest()->get();
    }

    public function findBroker($id)
    {
        return Broker::with('bankDetail')->findOrFail($id);
    }

    public function createBroker(array $data)
    {
        $broker = Broker::create([
            'name' => $data['name'],
            'mobile_number' => $data['mobile_number'],
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'pancard_number' => $data['pancard_number'] ?? null,
            'aadhar_number' => $data['aadhar_number'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        $broker->bankDetail()->create([
            'bank_name' => $data['bank_name'] ?? null,
            'account_number' => $data['account_number'] ?? null,
            'ifsc_code' => $data['ifsc_code'] ?? null,
            'account_holder_name' => $data['account_holder_name'] ?? null,
        ]);

        return $broker;
    }

    public function updateBroker($broker, array $data)
    {
        $broker->update([
            'name' => $data['name'],
            'mobile_number' => $data['mobile_number'],
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'pancard_number' => $data['pancard_number'] ?? null,
            'aadhar_number' => $data['aadhar_number'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        BrokerBankDetail::updateOrCreate(
            ['broker_id' => $broker->id],
            [
                'bank_name' => $data['bank_name'] ?? null,
                'account_number' => $data['account_number'] ?? null,
                'ifsc_code' => $data['ifsc_code'] ?? null,
                'account_holder_name' => $data['account_holder_name'] ?? null,
            ]
        );

        return $broker;
    }

    public function deleteBroker($broker)
    {
        return $broker->delete();
    }
}