<div class="row">
    <!-- Plot Type -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Plot Type</label>
        <select name="plot_type_id" class="form-select @error('plot_type_id') is-invalid @enderror">
            <option value="">Select Plot Type</option>
            @foreach ($plotTypes as $plotType)
                <option value="{{ $plotType->id }}"
                    {{ old('plot_type_id', $plcRate->plot_type_id ?? '') == $plotType->id ? 'selected' : '' }}>
                    {{ $plotType->plot_type_name }}
                </option>
            @endforeach
        </select>
        @error('plot_type_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Rate -->
    <div class="col-md-6 mb-3">
        <label class="form-label"> PLC Rate(%)</label>
        <input type="number" name="rate" step="0.01" class="form-control @error('rate') is-invalid @enderror"
            placeholder="Enter PLC Rate" value="{{ old('rate', $plcRate->rate ?? '') }}">
        @error('rate')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="text-end">
    <button type="submit" class="btn btn-success">Save</button>
</div>
