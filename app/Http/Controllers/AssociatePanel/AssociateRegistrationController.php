<?php

namespace App\Http\Controllers\AssociatePanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssociateRequest; // Wahi validation request jo pehle thi
use App\Models\Associate;
use App\Models\DesignationRank;
use App\Services\Associate\AssociateRegistrationService;
use App\Services\ExcelExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AssociateRegistrationController extends Controller
{
    protected $associateService;

    public function __construct(AssociateRegistrationService $associateService)
    {
        $this->associateService = $associateService;
    }

    public function associateDatail(Request $request)
    {
        $data = $this->associateService->getAssociateData($request);

        return view('associate-panel.registration.associate_detail', [
            'associates' => $data['associates'],
            'ranks' => $data['ranks'],
        ]);
    }

    public function create()
    {
        $data = $this->associateService->createData();
        $data['loggedInAssociate'] = auth()->user();

        return view('associate-panel.registration.create', $data);
    }

    public function getSponsorRanks($associateId)
    {
        $associate = Associate::where('associate_id', $associateId)->with('rank')->firstOrFail();
        $ranks = DesignationRank::where('rank_number', '<=', $associate->rank->rank_number)->orderByDesc('rank_number')->get();

        return response()->json($ranks);
    }

    public function store(AssociateRequest $request)
    {
        $this->associateService->store($request->validated());

        return redirect()->route('associate-panel.register-create')->with('success', 'Associate registered successfully.');
    }

    public function edit($id)
    {
        $data = $this->associateService->editData($id);
        $data['loggedInAssociate'] = auth()->user();

        return view('associate-panel.registration.edit', $data);
    }

    public function update(AssociateRequest $request, $id)
    {
        $this->associateService->update($request->validated(), $id);

        return redirect()->back()->with('success', 'Associate updated successfully.');
    }

    public function associateDelete($id)
    {
        $this->associateService->associateDelete($id);

        return redirect()->back()->with('success', 'Associate deleted successfully.');
    }

    public function associateExport(Request $request, ExcelExportService $excelExportService)
    {
        $associates = $this->associateService->getExportData($request);
        $headers = [
            'SNo.',
            'Sponsor Id',
            'Associate Id',
            'Under Place Id',
            'Associate Name',
            'Percentage',
            'D.O.B',
            'Address',
            'Mobile',
            'Pancard No',
            'Bank Name',
            'Account No',
            'IFSC Code',
            'Password',
            'Date',
            'Passbook',
            'IdProof',
            'Pancard',
        ];

        return $excelExportService->export($associates, 'associate-list', $headers, function ($associate) {
            return [
                $associate->id,
                $associate->sponsor_id,
                $associate->associate_id,
                $associate->under_place_id,
                $associate->associate_name,
                number_format($associate->rank?->commission, 2).' ('.$associate->rank?->designation.')',
                $associate->dob,
                $associate->address,
                $associate->mobile_number,
                $associate->pancard_number,
                $associate->bankDetail?->bank_name,
                $associate->bankDetail?->account_number,
                $associate->bankDetail?->ifsc_code,
                $associate->password ?? '',
                $associate->created_at?->format('d-M-y'),
                $associate->bankDetail?->bank_passbook ? 'Yes' : 'No',
                $associate->id_proof_photo ? 'Yes' : 'No',
                $associate->photo ? 'Yes' : 'No',
            ];
        }
        );
    }

    public function downloadPdf($id)
    {
        $associate = Associate::with('bankDetail')->findOrFail($id);

        // PDF render karna
        $pdf = Pdf::loadView('associate-panel.registration.pdf-view', compact('associate'));

        // File download karwana
        return $pdf->download('Prospect_'.$associate->associate_id.'.pdf');
    }
}
