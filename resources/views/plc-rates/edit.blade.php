@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Edit PLC Rate
                </h3>
                <p class="text-muted">
                    Edit PLC Rate
                </p>
            </div>
            <a href="{{ route('admin.plc-rates.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.plc-rates.update', $plcRate->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    @include('plc-rates.form')
                </form>
            </div>
        </div>
    </div>
@endsection
