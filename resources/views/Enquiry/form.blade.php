<form method="POST" id="enquiryForm" action="{{ route('enquiry.store') }}">
    @csrf
    <div id="methodField"></div>

    <div class="row g-3">
        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">Customer Name</label>
            <input type="text" name="customer_name" id="customer_name"
                class="form-control @error('customer_name') is-invalid @enderror" placeholder="Name" required
                autocomplete="off" value="{{ old('customer_name') }}">
            @error('customer_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">Mobile Number</label>
            <input type="text" name="mobile_number" id="mobile_number"
                class="form-control @error('mobile_number') is-invalid @enderror" placeholder="Mobile" required
                autocomplete="off" value="{{ old('mobile_number') }}">
            @error('mobile_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror" placeholder="Email" autocomplete="off"
                value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">DOB</label>
            <input type="date" name="dob" id="dob" class="form-control @error('dob') is-invalid @enderror"
                value="{{ old('dob') }}">
            @error('dob')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">Associate</label>
            <select name="associate_id" id="associate_id"
                class="form-select @error('associate_id') is-invalid @enderror">
                <option value="">Select Associate</option>
                @foreach ($associates as $associate)
                    <option value="{{ $associate->id }}" {{ old('associate_id') == $associate->id ? 'selected' : '' }}>
                        {{ $associate->associate_name }}
                    </option>
                @endforeach
            </select>
            @error('associate_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">Source</label>
            <select name="source_id" id="source_id" class="form-select @error('source_id') is-invalid @enderror">
                <option value="">Select Source</option>
                @foreach ($sources as $source)
                    <option value="{{ $source->id }}" {{ old('source_id') == $source->id ? 'selected' : '' }}>
                        {{ $source->name }}
                    </option>
                @endforeach
            </select>
            @error('source_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">Lead Type</label>
            <select name="enquiry_types_id" id="enquiry_types_id"
                class="form-select @error('enquiry_types_id') is-invalid @enderror">
                <option value="">Select Lead Type</option>
                @foreach ($enquiry_types as $type)
                    <option value="{{ $type->id }}" {{ old('enquiry_types_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('enquiry_types_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">Follow-up Date</label>
            <input type="date" name="followup_date" id="followup_date"
                class="form-control @error('followup_date') is-invalid @enderror" value="{{ old('followup_date') }}">
            @error('followup_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">State</label>
            <input type="text" name="state" id="state"
                class="form-control @error('state') is-invalid @enderror" placeholder="State"
                value="{{ old('state') }}">
            @error('state')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">City</label>
            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror"
                placeholder="City" value="{{ old('city') }}">
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">Plot Size (Sqft)</label>
            <input type="text" name="plot_size" id="plot_size"
                class="form-control @error('plot_size') is-invalid @enderror" placeholder="e.g. 1200"
                value="{{ old('plot_size') }}">
            @error('plot_size')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-3 col-md-6">
            <label class="form-label fw-semibold">Budget</label>
            <input type="text" name="budget" id="budget"
                class="form-control @error('budget') is-invalid @enderror" placeholder="e.g. 45 Lakhs"
                value="{{ old('budget') }}">
            @error('budget')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold">Preferred Location</label>
            <input type="text" name="location" id="location"
                class="form-control @error('location') is-invalid @enderror" placeholder="Enter preferred locations"
                value="{{ old('location') }}">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 mt-3">
            <div class="d-flex justify-content-end gap-2">
                <button type="button" id="cancelBtn" class="btn btn-outline-secondary px-4 d-none">Cancel</button>
                <button type="submit" id="submitBtn" class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i>
                    Save Enquiry
                </button>
            </div>
        </div>
    </div>
</form>
