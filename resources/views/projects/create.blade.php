@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Add Project
                </h3>

                <small class="text-muted">
                    Add a new project to the system
                </small>

            </div>

            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>

                Back

            </a>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <form method="POST" action="{{ route('admin.projects.store') }}">

                    @csrf

                    @include('projects.form')

                </form>

            </div>

        </div>

    </div>
@endsection
