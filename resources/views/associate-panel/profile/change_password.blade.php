@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4 bg-light min-vh-100">

        {{-- Form Start --}}
        <form method="POST" action="{{ route('associate-panel.update-password') }}">
            @csrf

            {{-- Header Block (Exactly same as Modify Profile) --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-white rounded-3">
                        <div
                            class="card-body d-flex flex-column flex-sm-row justify-content-between align-items-sm-center p-4 gap-3">
                            <div>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success mb-2 px-3 py-2 text-uppercase fw-bold fs-7">
                                    Security Workspace
                                </span>
                                <h2 class="mb-1 text-dark fw-bold h3 tracking-tight">Modify Password Credentials</h2>
                                <p class="mb-0 text-muted small fw-medium">
                                    Keep your account secure. Ensure your password is strong and updated regularly.
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="submit" class="btn btn-success px-4 py-2 fw-semibold shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2-Column Layout (Full Screen Grid like Modify Profile) --}}
            <div class="row g-4">

                {{-- Left Column: Security Guidelines (Fills the Left Side Space) --}}
                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                        <div class="card-header bg-transparent pt-4 px-4 pb-3 border-bottom border-light">
                            <h4 class="fw-bold mb-0 text-dark h5">
                                <i class="bi bi-shield-check text-success me-2"></i>Security Guidelines
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <h6 class="fw-bold text-dark mb-2"><i
                                        class="bi bi-info-circle-fill text-success me-2"></i>Password Requirements:</h6>
                                <ul class="text-muted small mb-0 ps-3">
                                    <li class="mb-2">Must be at least <b>8 characters</b> long.</li>
                                    <li class="mb-2">Should contain a mix of uppercase letters, numbers, and special
                                        characters (e.g., @, #, $).</li>
                                    <li>Do not use easily guessable names, birthdays, or mobile numbers.</li>
                                </ul>
                            </div>

                            <div class="p-3 border border-dashed rounded-3 mt-4">
                                <p class="mb-0 text-muted small fw-medium">
                                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                                    <b>Note:</b> After a successful password reset, you might need to login again with your
                                    new credentials to securely re-establish your session.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Change Password Inputs (Fills the Right Side Space) --}}
                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                        <div class="card-header bg-transparent pt-4 px-4 pb-3 border-bottom border-light">
                            <h4 class="fw-bold mb-0 text-dark h5">
                                <i class="bi bi-key-fill text-success me-2"></i>Update Password
                            </h4>
                        </div>
                        <div class="card-body p-4">

                            {{-- Success Message Alert inside the card --}}
                            @if (session('success'))
                                <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center p-3 mb-4"
                                    role="alert">
                                    <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
                                    <div class="fw-semibold text-success small">{{ session('success') }}</div>
                                </div>
                            @endif

                            <div class="row g-4">
                                {{-- Current Password --}}
                                <div class="col-12">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Current Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control fw-semibold @error('current_password') is-invalid @enderror"
                                            placeholder="Enter your old password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-target="current_password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- New Password --}}
                                <div class="col-12">
                                    <label class="form-label text-secondary fw-semibold small mb-1">New Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="new_password" id="new_password"
                                            class="form-control fw-semibold @error('new_password') is-invalid @enderror"
                                            placeholder="Enter minimum 8 characters password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-target="new_password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('new_password')
                                            <div class="invalid-feedback mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Confirm New Password --}}
                                <div class="col-12">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Confirm New Password
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="new_password_confirmation"
                                            id="new_password_confirmation" class="form-control fw-semibold"
                                            placeholder="Re-enter your new password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-target="new_password_confirmation">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer Action Buttons (Same as Modify Profile) --}}
            <div class="row mt-4">
                <div class="col-12 text-end border-top border-light-subtle pt-4 pb-2">
                    <a href="{{ route('associate-panel.view-profile') }}"
                        class="btn btn-outline-secondary px-4 py-2 me-2 fw-semibold">Cancel</a>
                    <button type="submit" class="btn btn-success px-5 py-2 fw-semibold shadow-sm">Save Password
                        Changes</button>
                </div>
            </div>

        </form>
    </div>

    {{-- Eye Icon functionally working karne ke liye chota sa JS --}}
@endsection
@push('scripts')
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    </script>
@endpush
