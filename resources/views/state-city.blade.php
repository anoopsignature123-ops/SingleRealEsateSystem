<div class="row g-3">

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            State <span class="text-danger">*</span>
        </label>

        <select name="state" id="state_id" class="form-select">
            <option value="">Select State</option>

            @foreach ($states as $state)
                <option value="{{ $state->id_state }}"
                    {{ old('state', $farmer->state ?? '') == $state->id_state ? 'selected' : '' }}>
                    {{ $state->state }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            City <span class="text-danger">*</span>
        </label>

        <select name="city" id="city_id" class="form-select">
            <option value="">Select City</option>
        </select>
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function () {

    let selectedState = "{{ old('state', $farmer->state ?? '') }}";
    let selectedCity  = "{{ old('city', $farmer->city ?? '') }}";

    function loadCities(stateId, selectedCity = '') {

        if (!stateId) {
            $('#city_id').html('<option value="">Select City</option>');
            return;
        }

        $.ajax({
            url: '/get-cities/' + stateId,
            type: 'GET',

            success: function (response) {

                let options = '<option value="">Select City</option>';

                $.each(response, function (index, city) {

                    let selected = city.city == selectedCity ? 'selected' : '';

                    options += `
                        <option value="${city.city}" ${selected}>
                            ${city.city}
                        </option>
                    `;
                });

                $('#city_id').html(options);
            }
        });
    }

    // State Change
    $('#state_id').on('change', function () {
        loadCities($(this).val());
    });

    // Edit Page Auto Load
    if (selectedState) {
        loadCities(selectedState, selectedCity);
    }

});
</script>
@endpush