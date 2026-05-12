@php
    $secondary = $customer?->secondaryDetail;
@endphp


<div class="card border-0 shadow-sm mb-4">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4 border-bottom pb-2">
            Secondary Applicant Details
        </h5>


        <div class="row">

            {{-- Full Name --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Full Name
                </label>

                <input type="text" name="secondary_name"
                    class="form-control @error('secondary_name') is-invalid @enderror"
                    placeholder="Enter secondary applicant name" value="{{ old('secondary_name', $secondary?->name) }}">

                @error('secondary_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            {{-- Relation Type --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Relation Type
                </label>

                <select name="secondary_title" class="form-select @error('secondary_title') is-invalid @enderror">

                    <option value="">
                        Select relation
                    </option>

                    <option value="s/o" {{ old('secondary_title', $secondary?->title) == 's/o' ? 'selected' : '' }}>
                        S/O
                    </option>

                    <option value="w/o" {{ old('secondary_title', $secondary?->title) == 'w/o' ? 'selected' : '' }}>
                        W/O
                    </option>

                    <option value="d/o" {{ old('secondary_title', $secondary?->title) == 'd/o' ? 'selected' : '' }}>
                        D/O
                    </option>

                    <option value="c/o" {{ old('secondary_title', $secondary?->title) == 'c/o' ? 'selected' : '' }}>
                        C/O
                    </option>

                </select>

                @error('secondary_title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            {{-- Relation Name --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Relation Name
                </label>

                <input type="text" name="secondary_relation_name"
                    class="form-control @error('secondary_relation_name') is-invalid @enderror"
                    placeholder="Enter relation name"
                    value="{{ old('secondary_relation_name', $secondary?->relation_name) }}">

                @error('secondary_relation_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            {{-- DOB --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Date Of Birth
                </label>

                <input type="date" name="secondary_dob"
                    class="form-control @error('secondary_dob') is-invalid @enderror"
                    value="{{ old('secondary_dob', $secondary?->dob) }}">

                @error('secondary_dob')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            {{-- Gender --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Gender
                </label>

                <select name="secondary_gender" class="form-select @error('secondary_gender') is-invalid @enderror">

                    <option value="">
                        Select gender
                    </option>

                    <option value="male"
                        {{ old('secondary_gender', $secondary?->gender) == 'male' ? 'selected' : '' }}>
                        Male
                    </option>

                    <option value="female"
                        {{ old('secondary_gender', $secondary?->gender) == 'female' ? 'selected' : '' }}>
                        Female
                    </option>

                </select>

                @error('secondary_gender')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            {{-- Pin --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Pin Code
                </label>

                <input type="text" id="secondaryPin" name="secondary_pin_code"
                    class="form-control @error('secondary_pin_code') is-invalid @enderror" placeholder="Enter pin code"
                    value="{{ old('secondary_pin_code', $secondary?->pin_code) }}">

                @error('secondary_pin_code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            {{-- City --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    City
                </label>

                <input type="text" id="secondaryCity" name="secondary_city"
                    class="form-control @error('secondary_city') is-invalid @enderror" placeholder="Enter city"
                    value="{{ old('secondary_city', $secondary?->city) }}">

                @error('secondary_city')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            {{-- State --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    State
                </label>

                <input type="text" id="secondaryState" name="secondary_state"
                    class="form-control @error('secondary_state') is-invalid @enderror" placeholder="Enter state"
                    value="{{ old('secondary_state', $secondary?->state) }}">

                @error('secondary_state')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            {{-- Address --}}
            <div class="col-md-12 mb-4">

                <label class="form-label">
                    Permanent Address
                </label>

                <textarea name="secondary_permanent_address" id="secondaryAddress" rows="3"
                    class="form-control @error('secondary_permanent_address') is-invalid @enderror"
                    placeholder="Enter complete permanent address">{{ old('secondary_permanent_address', $secondary?->permanent_address) }}</textarea>

                @error('secondary_permanent_address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>


        {{-- Secondary Correspondence --}}
        <hr>

        @include('customer-booking.partials.correspondence-form', [
            'prefix' => 'secondary_',
            'title' => 'Secondary Correspondence Details',
        ])

    </div>

</div>
