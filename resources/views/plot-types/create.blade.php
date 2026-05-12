@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Add Plot Type
                </h3>
                <small class="text-muted">
                    Add new plot type
                </small>
            </div>
            <a href="{{ route('admin.plot-types.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="card shadow border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.plot-types.store') }}">
                    @csrf

                    @include('plot-types.form')
                </form>
            </div>
        </div>
    </div>
@endsection
