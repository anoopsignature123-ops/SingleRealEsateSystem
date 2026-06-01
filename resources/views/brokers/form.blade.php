<div class="row g-3">
    <fieldset class="border rounded p-3 mb-4">
        <legend class="float-none w-auto px-2 fs-6 fw-bold text-success">
            Broker Information
        </legend>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Broker Name <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" 
                    value="{{ old('name', $broker->name ?? '') }}" 
                    class="form-control @error('name') is-invalid @enderror" 
                    placeholder="Enter Broker Name" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Mobile Number <span class="text-danger">*</span>
                </label>
                <input type="text" name="mobile_number" 
                    value="{{ old('mobile_number', $broker->mobile_number ?? '') }}" 
                    class="form-control @error('mobile_number') is-invalid @enderror" 
                    placeholder="Enter Mobile Number" required>
                @error('mobile_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- <div class="col-md-6">
                <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                <input type="text" name="city" 
                    value="{{ old('city', $broker->city ?? '') }}" 
                    class="form-control @error('city') is-invalid @enderror" 
                    placeholder="Enter City">
                @error('city')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                <input type="text" name="state" 
                    value="{{ old('state', $broker->state ?? '') }}" 
                    class="form-control @error('state') is-invalid @enderror" 
                    placeholder="Enter State">
                @error('state')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}

             @include('state-city', [
                'states' => $states,
                'selectedState' => old('state', $farmer->state ?? ''),
            ])

            <div class="col-md-6">
                <label class="form-label fw-semibold">PAN Card Number <span class="text-danger">*</span></label>
                <input type="text" name="pancard_number" 
                    value="{{ old('pancard_number', $broker->pancard_number ?? '') }}" 
                    class="form-control @error('pancard_number') is-invalid @enderror" 
                    placeholder="ABCDE1234F">
                @error('pancard_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Aadhaar Number <span class="text-danger">*</span></label>
                <input type="text" name="aadhar_number" 
                    value="{{ old('aadhar_number', $broker->aadhar_number ?? '') }}" 
                    class="form-control @error('aadhar_number') is-invalid @enderror" 
                    placeholder="Enter Aadhaar Number">
                @error('aadhar_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12">
                <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                <textarea name="address" rows="3" 
                    class="form-control @error('address') is-invalid @enderror" 
                    placeholder="Enter Full Address">{{ old('address', $broker->address ?? '') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </fieldset>

    <fieldset class="border rounded p-3">
        <legend class="float-none w-auto px-2 fs-6 fw-bold text-success">
            Bank Details
        </legend>
        @include('brokers.bank_details_form')
    </fieldset>
</div>

<div class="text-end mt-4">
    <button type="submit" class="btn btn-success px-4">
        <i class="bi bi-check-circle me-1"></i>
        {{ isset($broker) ? 'Update Broker' : 'Save Broker' }}
    </button>
</div>