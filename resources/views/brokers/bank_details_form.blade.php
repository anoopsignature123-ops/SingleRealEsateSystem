<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Bank Name <span class="text-danger">*</span></label>
        <input type="text" name="bank_name" 
            value="{{ old('bank_name', $broker->bankDetail->bank_name ?? '') }}"
            class="form-control @error('bank_name') is-invalid @enderror" 
            placeholder="Enter Bank Name">
        @error('bank_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Account Holder Name <span class="text-danger">*</span></label>
        <input type="text" name="account_holder_name"
            value="{{ old('account_holder_name', $broker->bankDetail->account_holder_name ?? '') }}"
            class="form-control @error('account_holder_name') is-invalid @enderror"
            placeholder="Enter Account Holder Name">
        @error('account_holder_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Account Number <span class="text-danger">*</span></label>
        <input type="text" name="account_number" 
            value="{{ old('account_number', $broker->bankDetail->account_number ?? '') }}"
            class="form-control @error('account_number') is-invalid @enderror" 
            placeholder="Enter Account Number">
        @error('account_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">IFSC Code <span class="text-danger">*</span></label>
        <input type="text" name="ifsc_code" 
            value="{{ old('ifsc_code', $broker->bankDetail->ifsc_code ?? '') }}"
            class="form-control text-uppercase @error('ifsc_code') is-invalid @enderror" 
            placeholder="e.g. SBIN0001234">
        @error('ifsc_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>