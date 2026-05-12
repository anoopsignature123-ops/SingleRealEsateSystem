@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div class="">
                <h3 class="fw-bold mb-1">
                    Edit Development
                </h3>

                <p class="text-muted">
                    Update development amount
                </p>
            </div>
            <a href="{{ route('admin.developments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <!-- Form Card -->
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <form action="{{ route('admin.developments.update', $development->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    @include('developments.form')

                </form>

            </div>

        </div>

    </div>
@endsection
