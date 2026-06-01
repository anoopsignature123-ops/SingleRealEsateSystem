@extends('layouts.app')

@section('content')
<div class="container-fluid py-4"> 
    {{-- PAGE HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">
                         <i class="bi bi-person-badge me-2 text-success"></i> Farmer Details
                    </h3>
                    <p class="text-muted mb-0 small">
                        Viewing profile for: <strong>{{ $farmer->name }}</strong>
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('farmers.edit', $farmer->id) }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
                        <i class="bi bi-pencil-square me-1"></i> Edit Details
                    </a>
                    <a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Personal Details --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 mb-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-success"><i class="bi bi-info-circle me-2"></i>Personal Details</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Farmer Name</span>
                            <span class="fw-semibold text-dark">{{ $farmer->name }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Assigned Broker</span>
                            <span class="fw-semibold text-primary">{{ $farmer->broker->name ?? 'Not Assigned' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Mobile Number</span>
                            <span class="fw-semibold">{{ $farmer->mobile_number }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Caste</span>
                            <span class="fw-semibold">{{ $farmer->caste ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">City / State</span>
                            <span class="fw-semibold">{{ $farmer->city ?? 'N/A' }}, {{ $farmer->state ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">PAN Number</span>
                            <span class="fw-semibold font-monospace">{{ $farmer->pancard_number ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Aadhaar Number</span>
                            <span class="fw-semibold font-monospace text-muted">{{ $farmer->aadhar_number }}</span>
                        </div>
                        <div class="list-group-item px-0 border-bottom-0">
                            <span class="text-muted d-block mb-1">Address</span>
                            <p class="mb-0 text-dark">{{ $farmer->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bank Details --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 mb-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-success"><i class="bi bi-bank me-2"></i>Bank Details</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Bank Name</span>
                            <span class="fw-semibold">{{ $farmer->bankDetail?->bank_name ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Account Holder</span>
                            <span class="fw-semibold">{{ $farmer->bankDetail?->account_holder_name ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Account Number</span>
                            <span class="fw-semibold font-monospace">{{ $farmer->bankDetail?->account_number ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between border-bottom-0">
                            <span class="text-muted">IFSC Code</span>
                            <span class="fw-semibold font-monospace text-uppercase">{{ $farmer->bankDetail?->ifsc_code ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection