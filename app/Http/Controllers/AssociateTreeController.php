<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use Illuminate\Http\Request;

class AssociateTreeController extends Controller
{
    public function index(Request $request)
    {
        $associateId = trim($request->associate_id ?? '');

        if ($associateId) {
            $rootAssociate = Associate::with('children.children')
                ->where('associate_id', $associateId)
                ->first();
        } else {
            $rootAssociate = Associate::with('children.children')
                ->whereNull('under_place_id')
                ->first();
        }

        return view(
            'associate-tree.index',
            compact('rootAssociate')
        );
    }
}
