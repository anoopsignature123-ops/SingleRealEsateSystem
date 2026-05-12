@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Edit Role
                </h3>

                <small class="text-muted">
                    Update role and permissions
                </small>

            </div>

            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>

                Back

            </a>
        </div>
        <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">

            @csrf
            @method('PUT')

            <!-- Role Name -->
            <div class="card shadow-sm mb-4 border-0">

                <div class="card-body">

                    <label class="fw-bold mb-2">
                        Role Name
                    </label>

                    <input type="text" name="name" value="{{ $role->name }}" class="form-control">

                </div>

            </div>


            @foreach ($modules as $module)
                {{-- Parent Module --}}
                @if ($module->children->count() > 0)
                    <div class="card shadow-sm mb-4 border-0">

                        <div class="card-header bg-success text-white py-2">

                            <h6 class="mb-0 fw-bold">

                                {{ $module->name }}

                            </h6>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                @foreach ($module->children as $child)
                                    <div class="col-md-4 col-lg-3 mb-3">

                                        <div class="card border h-100">

                                            <div class="card-body p-2">

                                                <h6 class="fw-bold small mb-2">

                                                    {{ $child->name }}

                                                </h6>

                                                @foreach ($actions as $action)
                                                    @php

                                                        $permissionName = $child->slug . '-' . $action;

                                                    @endphp

                                                    <div class="form-check mb-1">

                                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                                            id="{{ $child->slug }}-{{ $action }}"
                                                            value="{{ $permissionName }}"
                                                            {{ in_array($permissionName, $rolePermissions) ? 'checked' : '' }}>

                                                        <label class="form-check-label small"
                                                            for="{{ $child->slug }}-{{ $action }}">

                                                            {{ ucfirst($action) }}

                                                        </label>

                                                    </div>
                                                @endforeach

                                            </div>

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>

                    </div>
                @else
                    {{-- Single Module --}}
                    <div class="col-md-4 col-lg-3 mb-3 d-inline-block">

                        <div class="card shadow-sm border h-100">

                            <div class="card-header bg-success text-white py-2">

                                <h6 class="mb-0 fw-bold">

                                    {{ $module->name }}

                                </h6>

                            </div>

                            <div class="card-body p-2">

                                @foreach ($actions as $action)
                                    @php

                                        $permissionName = $module->slug . '-' . $action;

                                    @endphp

                                    <div class="form-check mb-1">

                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            id="{{ $module->slug }}-{{ $action }}" value="{{ $permissionName }}"
                                            {{ in_array($permissionName, $rolePermissions) ? 'checked' : '' }}>

                                        <label class="form-check-label small"
                                            for="{{ $module->slug }}-{{ $action }}">

                                            {{ ucfirst($action) }}

                                        </label>

                                    </div>
                                @endforeach

                            </div>

                        </div>

                    </div>
                @endif
            @endforeach


            <div class="mt-4">

                <button class="btn btn-success px-4">

                    Update Role

                </button>

            </div>

        </form>

    </div>
@endsection
