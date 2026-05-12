<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label"> Designation</label>
        <input type="text" name="designation" placeholder="Enter designation"
            class="form-control @error('designation') is-invalid @enderror"
            value="{{ old('designation', $designationRank->designation ?? '') }}">
        @error('designation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label"> Rank Number</label>
        <input type="number" name="rank_number" placeholder="Enter rank number"
            class="form-control @error('rank_number') is-invalid @enderror"
            value="{{ old('rank_number', $designationRank->rank_number ?? '') }}">
        @error('rank_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Commission</label>
        <input type="number" step="0.01" name="commission" placeholder="Enter commission"
            class="form-control @error('commission') is-invalid @enderror"
            value="{{ old('commission', $designationRank->commission ?? '') }}">
        @error('commission')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="text-end">
    <button class="btn btn-success"><i class="bi bi-check-circle"></i>Submit</button>
</div>
