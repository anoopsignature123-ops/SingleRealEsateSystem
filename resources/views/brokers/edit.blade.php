@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">
                        <i class="bi bi-pencil-square me-2 text-success"></i> Edit Broker
                    </h3>
                    <p class="text-muted mb-0 small">
                        Updating records for: <strong>{{ $broker->name }}</strong>
                    </p>
                </div>

                <a href="{{ route('brokers.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- FORM SECTION --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('brokers.update', $broker->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('brokers.form')

            </form>
        </div>
    </div>

</div>
@endsection