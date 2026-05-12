@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Associate Details
                </h3>

                <small class="text-muted">
                    Full associate information
                </small>

            </div>

            <a href="{{ route('admin.associate.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <!-- Basic Info -->
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header light">

                Basic Information

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <strong>Associate ID:</strong><br>
                        {{ $associate->associate_id }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Sponsor:</strong><br>
                        {{ $associate->sponsor?->associate_name ?? '-' }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Under Place:</strong><br>
                        {{ $associate->underPlace?->associate_name ?? '-' }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Name:</strong><br>
                        {{ $associate->associate_name }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Rank:</strong><br>
                        {{ $associate->rank?->designation ?? '-' }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Gender:</strong><br>
                        {{ ucfirst($associate->gender) }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Father/Husband:</strong><br>
                        {{ $associate->father_name }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>DOB:</strong><br>
                        {{ $associate->dob }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Mobile:</strong><br>
                        {{ $associate->mobile_number }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Email:</strong><br>
                        {{ $associate->email }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>PAN:</strong><br>
                        {{ $associate->pancard_number }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Aadhaar:</strong><br>
                        {{ $associate->aadhar_number }}
                    </div>

                    <div class="col-md-12 mb-3">
                        <strong>Address:</strong><br>
                        {{ $associate->address }}
                    </div>

                </div>

            </div>

        </div>


        <!-- Bank Details -->
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-light">

                Bank Details

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <strong>Bank:</strong><br>
                        {{ $associate->bankDetail?->bank_name ?? '-' }}
                    </div>

                    <div class="col-md-3">
                        <strong>Account No:</strong><br>
                        {{ $associate->bankDetail?->account_number ?? '-' }}
                    </div>

                    <div class="col-md-3">
                        <strong>IFSC:</strong><br>
                        {{ $associate->bankDetail?->ifsc_code ?? '-' }}
                    </div>

                    <div class="col-md-3">
                        <strong>Holder:</strong><br>
                        {{ $associate->bankDetail?->account_holder_name ?? '-' }}
                    </div>

                </div>

            </div>

        </div>


        <!-- Nominee -->
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header light">

                Nominee Details

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <strong>Name:</strong><br>
                        {{ $associate->bankDetail?->nominee_name ?? '-' }}
                    </div>

                    <div class="col-md-3">
                        <strong>Relation:</strong><br>
                        {{ $associate->bankDetail?->nominee_relation ?? '-' }}
                    </div>

                    <div class="col-md-3">
                        <strong>Age:</strong><br>
                        {{ $associate->bankDetail?->nominee_age ?? '-' }}
                    </div>

                    <div class="col-md-3">
                        <strong>Joining Date:</strong><br>
                        {{ $associate->bankDetail?->joining_date ?? '-' }}
                    </div>

                </div>

            </div>

        </div>


        <!-- Documents -->
        <div class="card shadow-sm border-0">

            <div class="card-header light">

                Documents

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <strong>Photo:</strong><br>

                        @if ($associate->photo)
                            <a href="{{ getFileUrl($associate->photo) }}" target="_blank">

                                View File

                            </a>
                        @else
                            -
                        @endif

                    </div>


                    <div class="col-md-3">

                        <strong>ID Proof:</strong><br>

                        @if ($associate->id_proof_photo)
                            <a href="{{ getFileUrl($associate->id_proof_photo) }}" target="_blank">

                                View File

                            </a>
                        @else
                            -
                        @endif

                    </div>
                    <div class="col-md-3">

                        <strong>Pancard Photo:</strong><br>

                        @if ($associate->pancard_photo)
                            <a href="{{ getFileUrl($associate->pancard_photo) }}" target="_blank">

                                View File

                            </a>
                        @else
                            -
                        @endif

                    </div>

                    <div class="col-md-3">

                        <strong>Passbook:</strong><br>

                        @if ($associate->bankDetail?->bank_passbook)
                            <a href="{{ getFileUrl($associate->bankDetail->bank_passbook) }}" target="_blank">

                                View File

                            </a>
                        @else
                            -
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
