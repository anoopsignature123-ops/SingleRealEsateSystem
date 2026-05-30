{{-- resources/views/associate-panel/team/my_tree.blade.php --}}

@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/tree.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">

        {{-- Header --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden position-relative"
            style="background-image: url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center;">

            <div class="position-absolute w-100 h-100"
                style="background: linear-gradient(135deg, rgba(48, 80, 58, 0.95) 0%, rgba(45, 146, 121, 0.9) 100%);">
            </div>

            <div class="card-body p-4 position-relative z-index-1">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">

                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-4 bg-white shadow-lg d-flex align-items-center justify-content-center fw-bold text-success border border-3 border-white"
                            style="width: 75px; height: 75px; font-size: 28px;">
                            {{ strtoupper(substr(auth()->user()->associate_name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="text-white">
                            <span class="badge bg-white bg-opacity-20 px-3 py-1 mb-1 rounded-pill"
                                style="font-size: 0.7rem; letter-spacing: 1px;">
                                ACTIVE ASSOCIATE
                            </span>
                            <h4 class="fw-bold mb-0">{{ auth()->user()->associate_name ?? 'Associate' }}</h4>
                            <small class="opacity-75">Team hierarchy & organization structure</small>
                        </div>
                    </div>

                    <form method="GET" class="p-2 rounded-4 shadow-sm"
                        style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <div class="d-flex gap-2">
                            <input type="text" name="associate_id" value="{{ request('associate_id') }}"
                                placeholder="Enter Associate ID" class="form-control border-0 bg-transparent text-white"
                                style="min-width: 220px; color: white !important;">

                            <button class="btn btn-success px-3 rounded-3 shadow-sm">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('associate-panel.my-tree') }}" class="btn btn-light px-3 rounded-3 shadow-sm">
                                Reset
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- Tree Card --}}
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                @if ($rootAssociate)
                    <div class="tree-container">

                        <div class="org-chart-wrapper">

                            @include('associate-panel.team.node', [
                                'associate' => $rootAssociate,
                            ])

                        </div>

                    </div>
                @else
                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i class="bi bi-diagram-3 fs-1 text-muted"></i>

                        </div>

                        <h5 class="fw-bold text-muted">

                            No Associate Found

                        </h5>

                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection
