@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Add Plot Rate
                </h3>
                <p class="text-muted">
                    Add new plot Rate
                </p>
            </div>
            <a href="{{ route('admin.plot-rates.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <form action="{{ route('admin.plot-rates.store') }}" method="POST">

                    @csrf

                    @include('plot-rates.form')

                </form>

            </div>

        </div>

    </div>
@endsection
