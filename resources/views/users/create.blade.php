@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Create User</h3>
                <small class="text-muted">Add a new user to the system</small>
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
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                Role
                            </label>
                            <select name="role" class="form-select">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                placeholder="Enter full name">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control "
                                placeholder="Enter email">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password">
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Confirm password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" id="imageInput"
                                accept="image/*">
                            @error('profile_image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="previewImage" src="{{ asset('assets/images/avatar.png') }}" alt="Preview"
                                    class="rounded border" style="height: 80px; width: 80px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- STATUS -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <!-- BUTTONS -->
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light me-2 border">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-circle"></i> Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewImage').src = event.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                document.getElementById('previewImage').src = "{{ asset('assets/images/avatar.png') }}";
            }
        });
    </script>
@endpush
