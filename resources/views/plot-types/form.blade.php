<div class="row">

    <div class="col-md-6 mb-3">

        <label class="mb-2">
            Plot Type Name
        </label>

        <input type="text" name="plot_type_name" value="{{ old('plot_type_name', $plotType->plot_type_name ?? '') }}"
            class="form-control" placeholder="Enter plot type">

        @error('plot_type_name')
            <small class="text-danger">

                {{ $message }}

            </small>
        @enderror

    </div>


    <div class="col-md-6 mb-3">

        <label class="mb-2">
            Date
        </label>

        <input type="date" name="date" value="{{ old('date', $plotType->date ?? '') }}" class="form-control">

    </div>

</div>


<button class="btn btn-success">

    Save Plot Type

</button>
