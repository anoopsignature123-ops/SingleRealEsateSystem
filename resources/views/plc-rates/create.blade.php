@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Add PLC Rate
                </h3>
                <p class="text-muted">
                    Add new PLC Rate
                </p>
            </div>
            <a href="{{ route('admin.plc-rates.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.plc-rates.store') }}" method="POST">

                    @csrf

                    @include('plc-rates.form')
                </form>
            </div>
        </div>
    </div>
@endsection
