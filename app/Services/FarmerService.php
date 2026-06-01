<?php

namespace App\Services;

use App\Models\Farmer;
use Illuminate\Support\Facades\DB;

class FarmerService
{
    public function getFarmers()
    {
        return Farmer::with('bankDetail', 'broker')->latest()->get();
    }

    public function findFarmer($id)
    {
        return Farmer::with('bankDetail', 'broker')->findOrFail($id);
    }

    public function createFarmer(array $data)
    {
        
        return DB::transaction(function () use ($data) {
            $farmer = Farmer::create($data);
            $farmer->bankDetail()->create($data);
            return $farmer;
        });
    }

    public function updateFarmer(Farmer $farmer, array $data)
    {
        return DB::transaction(function () use ($farmer, $data) {
            $farmer->update($data);
            $farmer->bankDetail()->updateOrCreate(
                ['farmer_id' => $farmer->id],
                $data
            );
            return $farmer;
        });
    }

    public function deleteFarmer(Farmer $farmer)
    {
        return $farmer->delete();
    }
}