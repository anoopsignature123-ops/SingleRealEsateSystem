@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4 bg-light min-vh-100">

        <form method="POST" action="{{ route('associate-panel.update-profile') }}">
            @csrf
            @method('post')

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-white rounded-3">
                        <div
                            class="card-body d-flex flex-column flex-sm-row justify-content-between align-items-sm-center p-4 gap-3">
                            <div>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success mb-2 px-3 py-2 text-uppercase fw-bold fs-7">
                                    Associate Workspace
                                </span>
                                <h2 class="mb-1 text-dark fw-bold h3 tracking-tight">Modify Profile Information</h2>
                                <p class="mb-0 text-muted small fw-medium">Keep your workspace records accurate. Ensure all
                                    data matches official records.</p>
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

            <div class="row g-4">

                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                        <div class="card-header bg-transparent pt-4 px-4 pb-3 border-bottom border-light">
                            <h4 class="fw-bold mb-0 text-dark h5">
                                <i class="bi bi-person-bounding-box text-success me-2"></i>Personal Details
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Sponsor Name</label>
                                    <input type="text"
                                        class="form-control bg-light text-muted border-secondary border-opacity-25 border-dashed"
                                        value="{{ $associate->sponsor->associate_name ?? '-' }}" disabled>
                                    <small class="text-muted d-block mt-1 fs-7">Sponsor details cannot be modified.</small>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Associate Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="associate_name"
                                        class="form-control text-uppercase fw-semibold @error('associate_name') is-invalid @enderror"
                                        value="{{ old('associate_name', $associate->associate_name) }}">
                                    @error('associate_name')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Gender <span
                                            class="text-danger">*</span></label>
                                    <select name="gender"
                                        class="form-select fw-semibold @error('gender') is-invalid @enderror">
                                        <option value="Male"
                                            {{ old('gender', $associate->gender) == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female"
                                            {{ old('gender', $associate->gender) == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="Other"
                                            {{ old('gender', $associate->gender) == 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Father/Husband Name
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="father_name"
                                        class="form-control fw-semibold @error('father_name') is-invalid @enderror"
                                        value="{{ old('father_name', $associate->father_name) }}">
                                    @error('father_name')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">DOB <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="dob"
                                        class="form-control font-monospace fw-semibold @error('dob') is-invalid @enderror"
                                        value="{{ old('dob', $associate->dob ? \Carbon\Carbon::parse($associate->dob)->format('Y-m-d') : '') }}">
                                    @error('dob')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                        <div class="card-header bg-transparent pt-4 px-4 pb-3 border-bottom border-light">
                            <h4 class="fw-bold mb-0 text-dark h5">
                                <i class="bi bi-shield-check text-success me-2"></i>Nominee's Details
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Nominee Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nominee_name"
                                        class="form-control fw-semibold @error('nominee_name') is-invalid @enderror"
                                        value="{{ old('nominee_name', $associate->bankDetail->nominee_name ?? '') }}">
                                    @error('nominee_name')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Nominee Relation <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nominee_relation"
                                        class="form-control fw-semibold @error('nominee_relation') is-invalid @enderror"
                                        value="{{ old('nominee_relation', $associate->bankDetail->nominee_relation ?? '') }}">
                                    @error('nominee_relation')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Nominee Age <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="nominee_age"
                                        class="form-control font-monospace fw-semibold @error('nominee_age') is-invalid @enderror"
                                        value="{{ old('nominee_age', $associate->bankDetail->nominee_age ?? '') }}">
                                    @error('nominee_age')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                        <div class="card-header bg-transparent pt-4 px-4 pb-3 border-bottom border-light">
                            <h4 class="fw-bold mb-0 text-dark h5">
                                <i class="bi bi-geo-alt-fill text-success me-2"></i>Address Information
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="address"
                                        class="form-control fw-semibold @error('address') is-invalid @enderror"
                                        value="{{ old('address', $associate->address) }}">
                                    @error('address')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">City <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="city"
                                        class="form-control fw-semibold @error('city') is-invalid @enderror"
                                        value="{{ old('city', $associate->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">State <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="state"
                                        class="form-control text-capitalize fw-semibold @error('state') is-invalid @enderror"
                                        value="{{ old('state', $associate->state) }}">
                                    @error('state')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Mobile <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="mobile_number"
                                        class="form-control font-monospace fw-semibold @error('mobile_number') is-invalid @enderror"
                                        value="{{ old('mobile_number', $associate->mobile_number) }}">
                                    @error('mobile_number')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email"
                                        class="form-control font-monospace text-lowercase fw-semibold @error('email') is-invalid @enderror"
                                        value="{{ old('email', $associate->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Pancard No <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="pancard_number"
                                        class="form-control font-monospace text-uppercase fw-semibold @error('pancard_number') is-invalid @enderror"
                                        value="{{ old('pancard_number', $associate->pancard_number) }}"
                                        style="letter-spacing: 0.5px;">
                                    @error('pancard_number')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Aadhaar No <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="aadhar_number"
                                        class="form-control font-monospace fw-semibold @error('aadhar_number') is-invalid @enderror"
                                        value="{{ old('aadhar_number', $associate->aadhar_number) }}"
                                        placeholder="Enter Aadhaar Number">
                                    @error('aadhar_number')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                        <div class="card-header bg-transparent pt-4 px-4 pb-3 border-bottom border-light">
                            <h4 class="fw-bold mb-0 text-dark h5">
                                <i class="bi bi-bank2 text-success me-2"></i>Bank Details
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Bank Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="bank_name"
                                        class="form-control text-uppercase fw-semibold @error('bank_name') is-invalid @enderror"
                                        value="{{ old('bank_name', $associate->bankDetail->bank_name ?? '') }}">
                                    @error('bank_name')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Account No <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="account_number"
                                        class="form-control font-monospace fw-semibold @error('account_number') is-invalid @enderror"
                                        value="{{ old('account_number', $associate->bankDetail->account_number ?? '') }}">
                                    @error('account_number')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-secondary fw-semibold small mb-1">IFSC Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="ifsc_code"
                                        class="form-control text-success font-monospace text-uppercase fw-bold @error('ifsc_code') is-invalid @enderror"
                                        value="{{ old('ifsc_code', $associate->bankDetail->ifsc_code ?? '') }}"
                                        style="letter-spacing: 0.5px;">
                                    @error('ifsc_code')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-secondary fw-semibold small mb-1">Account Holder Name
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="account_holder_name"
                                        class="form-control fw-semibold @error('account_holder_name') is-invalid @enderror"
                                        value="{{ old('account_holder_name', $associate->bankDetail->account_holder_name ?? '') }}">
                                    @error('account_holder_name')
                                        <div class="invalid-feedback fw-semibold mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-4">
                <div class="col-12 text-end border-top border-light-subtle pt-4 pb-2">
                    <a href="{{ url()->previous() }}"
                        class="btn btn-outline-secondary px-4 py-2 me-2 fw-semibold">Cancel</a>
                    <button type="submit" class="btn btn-success px-5 py-2 fw-semibold shadow-sm">Save Profile
                        Changes</button>
                </div>
            </div>

        </form>
    </div>
@endsection
