<div class="row">

    <div class="col-md-6 mb-3">

        <label class="mb-2">
            Site Name
        </label>

        <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}" class="form-control"
            placeholder="Enter site name">

        @error('name')
            <small class="text-danger">
                {{ $message }}
            </small>
        @enderror

    </div>


    <div class="col-md-6 mb-3">

        <label class="mb-2">
            Site Location
        </label>

        <input type="text" name="location" value="{{ old('location', $project->location ?? '') }}"
            class="form-control" placeholder="Enter site location">

        @error('location')
            <small class="text-danger">
                {{ $message }}
            </small>
        @enderror

    </div>


    <div class="col-md-6 mb-3">

        <label class="mb-2">
            Date
        </label>

        <input type="date" name="date" value="{{ old('date', $project->date ?? '') }}" class="form-control">

        @error('date')
            <small class="text-danger">
                {{ $message }}
            </small>
        @enderror

    </div>

</div>


<button class="btn btn-success">

    Save Project

</button>
