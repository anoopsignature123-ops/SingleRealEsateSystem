<?php

namespace App\Http\Controllers\AssociatePanel;

use App\Http\Controllers\Controller;
use App\Services\Associate\AssociateDashboardService;

class AssociateDashboardController extends Controller
{
    public function __construct(private AssociateDashboardService $service) {}

    public function dashboard()
    {
        $associate = auth()->user(); // Ya guard jo aap use kar rahe hain
        $data = $this->service->getDashboardStats($associate->id);

        return view('associate_dashboard', compact('associate', 'data'));
    }
}
