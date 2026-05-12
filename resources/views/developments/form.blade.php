<div class="mb-3">
    <label class="form-label">
        Development Amount
    </label>
    <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror"
        value="{{ old('amount', $development->amount ?? '') }}" placeholder="Enter amount">
    @error('amount')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
<div class="text-end">
    <button type="submit" class="btn btn-success">Save</button>
</div>
