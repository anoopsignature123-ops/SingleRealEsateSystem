@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Add Company</h3>
                <small class="text-muted">Add a new company to the system</small>
            </div>
            <a href="{{ route('admin.company.index') }}" class="btn btn-outline-secondary"><i
                    class="bi bi-arrow-left"></i>BacK</a>
        </div>
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.company.store') }}" enctype="multipart/form-data">
                    @csrf

                    @include('companies.form')
                </form>
            </div>
        </div>
    </div>
@endsection
