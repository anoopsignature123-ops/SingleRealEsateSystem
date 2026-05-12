<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Customer Type</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input customerType" type="radio" name="customer_type"
                        value="returning_customer" id="returningCustomer"
                        {{ old('customer_type', $customer->customer_type ?? '') == 'returning_customer' ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="returningCustomer">Returning Customer</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input customerType" type="radio" name="customer_type"
                        value="sale_customer" id="saleCustomer"
                        {{ old('customer_type', $customer->customer_type ?? '') == 'sale_customer' ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="saleCustomer">Sale Customer</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input customerType" type="radio" name="customer_type"
                        value="sale_to_associate" id="saleToAssociate"
                        {{ old('customer_type', $customer->customer_type ?? '') == 'sale_to_associate' ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="saleToAssociate">Sale To Associate</label>
                </div>
            </div>
            @error('customer_type')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>
<div id="returningCustomerSection" class="card shadow-sm border-0 mb-4" style="display:none;">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Existing Customer Details</h5>
        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Select Customer</label>
                <select name="existing_customer_id" id="existingCustomer" class="form-select">
                    <option value="">Select customer</option>
                    @foreach ($customers as $existingCustomer)
                        <option value="{{ $existingCustomer->id }}" data-code="{{ $existingCustomer->customer_code }}"
                            data-name="{{ $existingCustomer->customer_name }}"
                            {{ old('existing_customer_id', $customer->customer_id ?? '') == $existingCustomer->id ? 'selected' : '' }}>
                            {{ $existingCustomer->customer_code }} / {{ $existingCustomer->customer_name }}
                        </option>
                    @endforeach
                </select>
                @error('existing_customer_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Customer Code</label>
                <input type="text" name="customer_id" id="customerCode" class="form-control" readonly
                    placeholder="Auto filled" value="{{ old('customer_id', $customer->customer_code ?? '') }}">
                @error('customer_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Associate Details</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Select Associate</label>
                <select name="associate_id" id="associateSelect" class="form-select">
                    <option value="">Select associate</option>
                    @foreach ($associates as $associate)
                        <option value="{{ $associate->id }}" data-code="{{ $associate->associate_id }}"
                            data-name="{{ $associate->associate_name }}"
                            {{ old('associate_id', $customer->associate_id ?? '') == $associate->id ? 'selected' : '' }}>
                            {{ $associate->associate_id }} / {{ $associate->associate_name }}
                        </option>
                    @endforeach
                </select>
                @error('associate_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Associate Code</label>
                <input type="text" name="associate_code" id="associateCode" class="form-control" readonly
                    placeholder="Auto filled" value="{{ old('associate_code', $customer->associate_code ?? '') }}">
                @error('associate_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Associate Name</label>
                <input type="text" name="associate_name" id="associateName" class="form-control" readonly
                    placeholder="Auto filled" value="{{ old('associate_name', $customer->associate_name ?? '') }}">
                @error('associate_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>
<div class="text-end">
    <button type="submit" class="btn btn-success px-4">Save & Next</button>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            function toggleCustomerSection() {
                let selectedType =
                    $('input[name="customer_type"]:checked').val();
                if (selectedType == 'returning_customer') {
                    $('#returningCustomerSection').slideDown();
                } else {
                    $('#returningCustomerSection').slideUp();
                }
            }
            toggleCustomerSection();
            $('.customerType').change(function() {
                toggleCustomerSection();
            });
            $('#associateSelect').change(function() {
                let selected = $(this).find(':selected');
                $('#associateCode').val(selected.data('code') ?? '');
                $('#associateName').val(selected.data('name') ?? '');
            });
            $('#existingCustomer').change(function() {
                let selected = $(this).find(':selected');
                $('#customerCode').val(selected.data('code') ?? '');
            });

            $('#associateSelect').trigger('change');
            $('#existingCustomer').trigger('change');
        });
    </script>
@endpush
