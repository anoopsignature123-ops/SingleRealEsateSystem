<div class="row g-3">
    {{-- Site Name --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Site Name <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-geo-alt"></i></span>
            <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror" placeholder="Enter site name">
        </div>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Site Location --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Site Location <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-map"></i></span>
            <input type="text" name="location" value="{{ old('location', $project->location ?? '') }}"
                class="form-control @error('location') is-invalid @enderror" placeholder="Enter site location">
        </div>
        @error('location')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Date --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Date <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-calendar3"></i></span>
            <input type="date" name="date" value="{{ old('date', $project->date ?? '') }}"
                class="form-control @error('date') is-invalid @enderror">
        </div>
        @error('date')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Form Action Button --}}
<div class="text-end mt-4">
    <button type="submit" class="btn btn-success px-4 fw-semibold shadow-sm">
        <i class="bi bi-check-circle me-1"></i> Save Project
    </button>
</div>
