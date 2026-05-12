<div class="row">

    {{-- Company Name --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
            Company Name
        </label>
        <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}" class="form-control"
            placeholder="Enter company name">
        @error('name')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    {{-- Email --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
            Email Address
        </label>
        <input type="email" name="email" value="{{ old('email', $company->email ?? '') }}" class="form-control"
            placeholder="Enter email address">
        @error('email')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    {{-- Website --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
            Website
        </label>
        <input type="text" name="website_link" value="{{ old('website_link', $company->website_link ?? '') }}"
            class="form-control" placeholder="https://example.com">
        @error('website_link')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    {{-- Contact --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
            Contact Number
        </label>
        <input type="text" name="contact_no" value="{{ old('contact_no', $company->contact_no ?? '') }}"
            class="form-control" placeholder="Enter contact number">
        @error('contact_no')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    {{-- Address --}}
    <div class="col-md-12 mb-3">
        <label class="form-label fw-semibold">
            Address
        </label>
        <textarea name="address" rows="3" class="form-control" placeholder="Enter company address">{{ old('address', $company->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    {{-- Logo --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
            Company Logo
        </label>
        <input type="file" name="logo" id="logoInput" accept="image/*" class="form-control">
        @error('logo')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    {{-- Preview --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
            Preview
        </label>
        <div>
            <img id="previewLogo"
                src="{{ isset($company) ? getFileUrl($company->logo) : asset('assets/images/avatar.png') }}"
                class="rounded border" style="height:80px; width:80px; object-fit:cover;">
        </div>
    </div>
</div>
<div class="text-end mt-3">
    <button type="submit" class="btn btn-success px-4"> Save Company</button>
</div>
@push('scripts')
    <script>
        document.getElementById(
            'logoInput'
        ).addEventListener(
            'change',
            function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById(
                            'previewLogo'
                        ).src = event.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }
        );
    </script>
@endpush
