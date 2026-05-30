@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-pencil-square me-2 text-success"></i> Edit User: {{ $user->name }}
                        </h3>
                        <p class="text-muted mb-0 small">Update user details and role permissions.</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back to Users
                    </a>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')      
                    <div class="row g-4">  
                        {{-- ROLE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Assign Role</label>
                            <select name="role" class="form-select form-select-lg">
                                <option value="">Select a role...</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ (old('role', $user->roles->pluck('name')->first()) == $role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control form-control" placeholder="e.g. John Doe">
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control form-control" placeholder="name@company.com">
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Status</label>
                            <select name="status" class="form-select form-select-lg">
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Profile Image</label>
                            <div class="d-flex align-items-center gap-4 p-3 bg-light rounded-4 border border-light">
                                <div class="position-relative">
                                    <img id="previewImage" 
                                         src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/images/avatar.png') }}" 
                                         class="rounded-circle border border-2 border-white shadow-sm" 
                                         style="height: 80px; width: 80px; object-fit: cover;">
                                    <div class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-1" style="font-size: 10px;">
                                        <i class="bi bi-camera-fill"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">Change Profile Photo</div>
                                    <div class="text-muted small mb-2">JPG, PNG, JPEG Max size 2MB.</div>
                                    <label for="imageInput" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-none">Choose File</label>
                                    <input type="file" name="profile_image" id="imageInput" class="d-none" accept="image/*">
                                </div>
                            </div>
                            @error('profile_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-success px-5 rounded-pill shadow-sm">
                            <i class="bi bi-check-circle me-2"></i> Update User
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
                reader.onload = e => document.getElementById('previewImage').src = e.target.result;
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush