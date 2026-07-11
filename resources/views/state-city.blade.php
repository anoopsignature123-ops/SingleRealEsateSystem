@php($stateCityColumnClass = $stateCityColumnClass ?? 'col-md-6')

<div class="{{ $stateCityColumnClass }}">
    <label class="form-label fw-semibold">
        State <span class="text-danger">*</span>
    </label>
    <select name="state" id="state_id" class="form-select @error('state') is-invalid @enderror">
        <option value="">Select State</option>
        @foreach ($states as $state)
            <option value="{{ $state->id_state }}" {{ (string)($selectedState ?? '') === (string)$state->id_state ? 'selected' : '' }}>
                {{ $state->state }}
            </option>
        @endforeach
    </select>
    <div class="invalid-feedback state-error"></div>
</div>

<div class="{{ $stateCityColumnClass }}">
    <label class="form-label fw-semibold">
        City <span class="text-danger">*</span>
    </label>
    <select name="city" id="city_id" class="form-select @error('city') is-invalid @enderror">
        <option value="">Select City</option>
    </select>
    <div class="invalid-feedback city-error"></div>
</div>

@push('scripts')
<script>
$(function() {
    const $stateSelect = $('#state_id');
    const $citySelect = $('#city_id');
    
    // Initial values
    const selectedState = "{{ $selectedState ?? '' }}";
    const selectedCity = "{{ $selectedCity ?? '' }}";

    function loadCities(stateId, cityToSelect = '') {
        if (!stateId) {
            $citySelect.html('<option value="">Select City</option>');
            return;
        }

        const citiesUrl = "{{ $citiesUrl ?? '/get-cities/__STATE_ID__' }}".replace('__STATE_ID__', stateId);

        $.get(citiesUrl, function(response) {
            let options = '<option value="">Select City</option>';
            
            response.forEach(city => {
                const isSelected = (city.city == cityToSelect) ? 'selected' : '';
                options += `<option value="${city.city}" ${isSelected}>${city.city}</option>`;
            });
            
            $citySelect.html(options);
        }).fail(() => {
            console.error("Error loading cities");
        });
    }
    $stateSelect.on('change', function() {
        $stateSelect.removeClass('is-invalid');
        loadCities($(this).val());
    });
    if (selectedState) {
        loadCities(selectedState, selectedCity);
    }
    $('form').on('submit', function(e) {
        let valid = true;

        if (!$stateSelect.val()) {
            $stateSelect.addClass('is-invalid');
            $('.state-error').text('Please select a state');
            valid = false;
        }

        if (!$citySelect.val()) {
            $citySelect.addClass('is-invalid');
            $('.city-error').text('Please select a city');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });
});
</script>
@endpush
