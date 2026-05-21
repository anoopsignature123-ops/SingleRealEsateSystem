<div class="row g-4">
    {{-- Plot Type Name --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary mb-2">Plot Type Name <span
                class="text-danger">*</span></label>
        <div class="d-flex align-items-stretch">
            <span class="input-group-text bg-light text-muted px-3 border-end-0 rounded-start rounded-0"
                style="border: 1px solid #ced4da;">
                <i class="bi bi-grid"></i>
            </span>
            <input type="text" name="plot_type_name"
                value="{{ old('plot_type_name', $plotType->plot_type_name ?? '') }}"
                class="form-control rounded-start-0 @error('plot_type_name') is-invalid @enderror"
                placeholder="Enter plot type" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
        </div>
        @error('plot_type_name')
            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Date --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary mb-2">Date <span class="text-danger">*</span></label>
        <div class="d-flex align-items-stretch">
            <span class="input-group-text bg-light text-muted px-3 border-end-0 rounded-start rounded-0"
                style="border: 1px solid #ced4da;">
                <i class="bi bi-calendar3"></i>
            </span>
            <input type="date" name="date" value="{{ old('date', $plotType->date ?? '') }}"
                class="form-control rounded-start-0 @error('date') is-invalid @enderror"
                style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
        </div>
        @error('date')
            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Form Action Button --}}
<div class="text-end mt-4">
    <button type="submit" class="btn btn-success px-4 fw-semibold shadow-sm py-2">
        <i class="bi bi-check-circle me-1"></i> Save Plot Type
    </button>
</div>
