@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Edit Plot
                </h3>
                <small class="text-muted">
                    Edit plot information
                </small>
            </div>
            <a href="{{ route('admin.plot-details.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="card shadow border-0">

            <div class="card-body">

                <form method="POST" action="{{ route('admin.plot-details.update', $plotDetail->id) }}">

                    @csrf
                    @method('PUT')

                    @include('plot-details.form')

                </form>

            </div>

        </div>

    </div>
@endsection
