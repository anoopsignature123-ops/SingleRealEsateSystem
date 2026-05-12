@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Edit User</h3>
                <small class="text-muted">Update user information</small>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                ← Back
            </a>
        </div>
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header fw-semibold">
                User Information
            </div>
            <div class="card-body px-4 py-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                Role
                            </label>
                            <select name="role" class="form-select">
                                <option value="">
                                    Select Role
                                </option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- NAME -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control"
                                placeholder="Enter full name">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control" placeholder="Enter email">

                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" id="imageInput"
                                accept="image/*">
                            @error('profile_image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="previewImage" src="{{ getFileUrl($user->profile_image) }}" class="rounded border"
                                    style="height: 80px; width: 80px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light me-2 border">Cancel</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-circle"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
