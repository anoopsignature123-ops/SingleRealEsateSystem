@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div class="">
                <h3 class="fw-bold mb-1">
                    Add Development
                </h3>

                <p class="text-muted">
                    Add development amount
                </p>
            </div>
            <a href="{{ route('admin.developments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.developments.store') }}" method="POST">

                    @csrf

                    @include('developments.form')

                </form>

            </div>

        </div>

    </div>
@endsection
