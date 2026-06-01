<div class="row g-3">
    <fieldset class="border rounded p-3 mb-4">
        <legend class="float-none w-auto px-2 fs-6 fw-bold text-success">
            Farmer Information
        </legend>

        <div class="row g-3">
            {{-- Broker Selection --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">Select Broker <span class="text-danger">*</span></label>
                <select name="broker_id" class="form-select @error('broker_id') is-invalid @enderror" required>
                    <option value="">Select Broker</option>
                    @foreach ($brokers as $broker)
                        <option value="{{ $broker->id }}"
                            {{ old('broker_id', $farmer->broker_id ?? '') == $broker->id ? 'selected' : '' }}>
                            {{ $broker->name }}
                        </option>
                    @endforeach
                </select>
                @error('broker_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Farmer Name --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">Farmer Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $farmer->name ?? '') }}"
                    class="form-control @error('name') is-invalid @enderror" placeholder="Enter Farmer Name" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Caste --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">Caste <span class="text-danger">*</span></label>
                <select name="caste" class="form-select @error('caste') is-invalid @enderror" required>
                    <option value="">Select Caste</option>
                    @foreach (['General', 'OBC', 'SC', 'ST'] as $caste)
                        <option value="{{ $caste }}"
                            {{ old('caste', $farmer->caste ?? '') == $caste ? 'selected' : '' }}>{{ $caste }}
                        </option>
                    @endforeach
                </select>
                @error('caste')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mobile Number --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                <input type="text" name="mobile_number"
                    value="{{ old('mobile_number', $farmer->mobile_number ?? '') }}"
                    class="form-control @error('mobile_number') is-invalid @enderror" placeholder="Enter Mobile Number"
                    required>
                @error('mobile_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- City & State --}}
            {{-- <div class="col-md-6">
                <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                <input type="text" name="city" value="{{ old('city', $farmer->city ?? '') }}" 
                    class="form-control" placeholder="Enter City">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                <input type="text" name="state" value="{{ old('state', $farmer->state ?? '') }}" 
                    class="form-control" placeholder="Enter State">
            </div> --}}

            @include('state-city', [
                'states' => $states,
                'selectedState' => old('state', $farmer->state ?? ''),
            ])

            {{-- ID Proofs --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">PAN Card Number <span class="text-danger">*</span></label>
                <input type="text" name="pancard_number"
                    value="{{ old('pancard_number', $farmer->pancard_number ?? '') }}" class="form-control"
                    placeholder="ABCDE1234F">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Aadhaar Number <span class="text-danger">*</span></label>
                <input type="text" name="aadhar_number"
                    value="{{ old('aadhar_number', $farmer->aadhar_number ?? '') }}" class="form-control"
                    placeholder="Enter [Aadhaar Redacted]">
            </div>

            {{-- Address --}}
            <div class="col-md-12">
                <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                <textarea name="address" rows="2" class="form-control" placeholder="Enter Full Address">{{ old('address', $farmer->address ?? '') }}</textarea>
            </div>
        </div>
    </fieldset>

    <fieldset class="border rounded p-3">
        <legend class="float-none w-auto px-2 fs-6 fw-bold text-success">
            Bank Details
        </legend>
        @include('farmers.bank_details_form')
    </fieldset>
</div>

<div class="text-end mt-4">
    <button type="submit" class="btn btn-success px-4">
        <i class="bi bi-check-circle me-1"></i>
        {{ isset($farmer) ? 'Update Farmer' : 'Save Farmer' }}
    </button>
</div>
