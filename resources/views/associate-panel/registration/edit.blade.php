@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Edit & Update Associate</h3>
            </div>
        </div>
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <form method="POST" action="{{ route('associate-panel.associate-update', $associate->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="p-4">
                        @include('associate-panel.registration.form')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
