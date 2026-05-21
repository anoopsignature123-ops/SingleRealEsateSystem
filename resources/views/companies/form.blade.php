<div class="row g-3">
    {{-- Company Name --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Company Name <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
            <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror" placeholder="Enter company name">
        </div>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email Address --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Email Address <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" value="{{ old('email', $company->email ?? '') }}"
                class="form-control @error('email') is-invalid @enderror" placeholder="Enter email address">
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Website Link --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Website URL</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-globe"></i></span>
            <input type="text" name="website_link" value="{{ old('website_link', $company->website_link ?? '') }}"
                class="form-control @error('website_link') is-invalid @enderror" placeholder="https://example.com">
        </div>
        @error('website_link')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Contact Number --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Contact Number <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
            <input type="text" name="contact_no" value="{{ old('contact_no', $company->contact_no ?? '') }}"
                class="form-control @error('contact_no') is-invalid @enderror" placeholder="Enter contact number">
        </div>
        @error('contact_no')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Company Logo</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-image"></i></span>
            <input type="file" name="logo" id="logoInput" accept="image/*"
                class="form-control @error('logo') is-invalid @enderror">
        </div>
        @error('logo')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Address --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Full Address <span class="text-danger">*</span></label>
        <textarea name="address" rows="1" class="form-control @error('address') is-invalid @enderror"
            placeholder="Enter company operations office address">{{ old('address', $company->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Preview Card Layout --}}
    <div class="col-md-12 my-2">
        <div class="p-2 border rounded bg-light d-inline-flex align-items-center gap-3">
            <span class="small fw-bold text-muted ps-1">Logo Preview:</span>
            <img id="previewLogo"
                src="{{ isset($company) ? getFileUrl($company->logo) : asset('assets/images/avatar.png') }}"
                class="rounded border shadow-sm" style="height:65px; width:65px; object-fit:cover;">
        </div>
    </div>
</div>

<div class="text-end mt-4">
    <button type="submit" class="btn btn-success px-4 fw-semibold shadow-sm">
        <i class="bi bi-check-circle me-1"></i> Save Company Profile
    </button>
</div>

@push('scripts')
    <script>
        document.getElementById('logoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewLogo').src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush