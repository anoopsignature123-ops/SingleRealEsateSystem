@extends('layouts.app')

@section('content')
<div class="container-fluid py-4"> 
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">
                         <i class="bi bi-person-badge me-2 text-success"></i> Broker Deatails
                    </h3>
                    <p class="text-muted mb-0 small">
                        Viewing details for: <strong>{{ $broker->name }}</strong>
                    </p>
                </div>

                <a href="{{ route('brokers.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>


    <div class="row">
        {{-- Broker Information --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 mb-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-success"><i class="bi bi-info-circle me-2"></i>Personal Details</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Broker Name</span>
                            <span class="fw-semibold text-dark">{{ $broker->name }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Mobile Number</span>
                            <span class="fw-semibold">{{ $broker->mobile_number }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">City / State</span>
                            <span class="fw-semibold">{{ $broker->city ?? 'N/A' }}, {{ $broker->state ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">PAN Number</span>
                            <span class="fw-semibold font-monospace">{{ $broker->pancard_number ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Aadhaar Number</span>
                            <span class="fw-semibold font-monospace">{{ $broker->aadhar_number ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0">
                            <span class="text-muted d-block mb-1">Address</span>
                            <p class="mb-0 text-dark">{{ $broker->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bank Information --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 mb-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-success"><i class="bi bi-bank me-2"></i>Bank Details</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Bank Name</span>
                            <span class="fw-semibold">{{ $broker->bankDetail?->bank_name ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Account Holder</span>
                            <span class="fw-semibold">{{ $broker->bankDetail?->account_holder_name ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Account Number</span>
                            <span class="fw-semibold font-monospace">{{ $broker->bankDetail?->account_number ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">IFSC Code</span>
                            <span class="fw-semibold font-monospace text-uppercase">{{ $broker->bankDetail?->ifsc_code ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection