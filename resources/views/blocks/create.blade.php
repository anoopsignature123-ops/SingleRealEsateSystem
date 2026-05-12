@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Add Block
                </h3>

                <small class="text-muted">
                    Add a new block to the system
                </small>

            </div>

            <a href="{{ route('admin.blocks.index') }}" class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>

                Back

            </a>

        </div>


        <!-- Card -->
        <div class="card shadow border-0">

            <div class="card-body">

                <form method="POST" action="{{ route('admin.blocks.store') }}">

                    @csrf

                    @include('blocks.form')

                </form>

            </div>

        </div>

    </div>
@endsection
