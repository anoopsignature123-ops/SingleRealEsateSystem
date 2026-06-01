<?php

namespace App\Services;

use App\Models\City;
use App\Models\State;

class LocationService
{
    public function getStates()
    {
        return State::where('country_id', 101)
            ->orderBy('state')
            ->get();
    }

    public function getCities($stateId)
    {
        return City::where('state_id', $stateId)
            ->orderBy('city')
            ->get();
    }
}