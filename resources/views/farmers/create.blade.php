@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">
                        <i class="bi bi-person-plus-fill me-2 text-success"></i> Create New Broker
                    </h3>
                    <p class="text-muted mb-0 small">
                        Fill in the details below to onboard a new broker into the system.
                    </p>
                </div>

                <a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- FORM SECTION --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('farmers.store') }}" method="POST">
                @csrf
                
                @include('farmers.form')

            </form>
        </div>
    </div>

</div>
@endsection