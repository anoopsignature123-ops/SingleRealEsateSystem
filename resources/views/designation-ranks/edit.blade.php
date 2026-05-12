@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Edit Designation
                </h3>
                <small class="text-muted">
                    Edit Designation
                </small>
            </div>
            <a href="{{ route('admin.designations.index') }}" class="btn btn-outline-secondary"><i
                    class="bi bi-arrow-left"></i>BacK</a>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.designations.update', $designationRank->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('designation-ranks.form')
                </form>
            </div>
        </div>
    </div>
@endsection
