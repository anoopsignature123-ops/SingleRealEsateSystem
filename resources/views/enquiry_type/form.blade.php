<form method="POST" id="enquiryTypeForm" action="{{ route('enquiry-type.store') }}">
    @csrf
    <div id="methodField"></div>
    <div class="row g-3 align-items-end">
        <div class="col-lg-8">
            <label class="form-label fw-semibold">Lead Type Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                placeholder="Enter Lead Type Name" required autocomplete="off" value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-4">
            <div class="d-flex justify-content-lg-end gap-2">
                <button type="button" id="cancelBtn" class="btn btn-outline-secondary px-4 d-none">Cancel</button>
                <button type="submit" id="submitBtn" class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i>
                    Save Type
                </button>
            </div>
        </div>
    </div>
</form>
