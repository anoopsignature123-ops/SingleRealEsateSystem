@php
    $plotSale = $plotSale ?? null;
@endphp

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4 border-bottom pb-2">
            Plot Sale Details
        </h5>

        <div class="row">

            {{-- Property Name --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Property Name
                </label>

                <select name="project_id" id="projectId" class="form-select @error('project_id') is-invalid @enderror">

                    <option value="">
                        Select Property
                    </option>

                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}"
                            {{ old('project_id', $plotSale?->project_id) == $project->id ? 'selected' : '' }}>

                            {{ $project->name }}

                        </option>
                    @endforeach

                </select>

                @error('project_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Block --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Block
                </label>

                <select name="block_id" id="blockId" class="form-select @error('block_id') is-invalid @enderror">

                    <option value="">
                        Select Block
                    </option>

                </select>

                @error('block_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Show Plot Button --}}
            <div class="col-md-12 mb-3">

                <button type="button" id="showPlots"
                    class="btn btn-light {{ old('block_id', $plotSale?->block_id) ? '' : 'd-none' }}">

                    Show Available Plots

                </button>

            </div>


            {{-- Dynamic Plot Cards --}}
            <div class="col-md-12 mb-4">

                <div id="plotListSection"></div>

            </div>


            {{-- Hidden Plot ID --}}
            <input type="hidden" name="plot_detail_id" id="plotId"
                value="{{ old('plot_detail_id', $plotSale?->plot_detail_id) }}">


            {{-- Plot Number --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Plot Number
                </label>

                <input type="text" id="plotNumber" class="form-control" name="plot_number" readonly
                    value="{{ old('plot_number', $plotSale?->plotDetail?->plot_number) }}"
                    placeholder="Auto selected plot number">

            </div>


            {{-- Development Charge --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Total Development Charge
                </label>

                <input type="text" name="total_development_charge"
                    class="form-control @error('total_development_charge') is-invalid @enderror"
                    value="{{ old('total_development_charge', $plotSale?->total_development_charge) }}"
                    placeholder="Enter development charge">

                @error('total_development_charge')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Development Rate --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Development Rate (Per Sq.Ft)
                </label>

                <input type="text" name="development_rate" id="developmentRate"
                    class="form-control @error('development_rate') is-invalid @enderror"
                    value="{{ old('development_rate', $plotSale?->development_rate) }}"
                    placeholder="Enter development rate">

                @error('development_rate')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Plot Rate --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Plot Rate
                </label>
                <input type="text" name="plot_rate" id="plotRate" class="form-control" readonly
                    value="{{ old('plot_rate', $plotSale?->plot_rate) }}" placeholder="Auto filled plot rate">
            </div>
            {{-- Plot Area --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Plot Area (Per Sq.Ft)
                </label>

                <input type="text" name="plot_area" id="plotArea" class="form-control" readonly
                    value="{{ old('plot_area', $plotSale?->plot_area) }}" placeholder="Auto filled plot area">

            </div>


            {{-- Plot Cost --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Plot Cost
                </label>

                <input type="text" name="plot_cost" id="plotCost" class="form-control" readonly
                    value="{{ old('plot_cost', $plotSale?->plot_cost) }}" placeholder="Auto calculated plot cost">

            </div>


            {{-- PLC Amount --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    PLC Amount
                </label>

                <input type="text" name="plc_amount" id="plcAmount" class="form-control" readonly
                    value="{{ old('plc_amount', $plotSale?->plc_amount) }}" placeholder="Auto calculated PLC amount">

            </div>


            {{-- Other Charges --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Other Charges
                </label>

                <input type="text" name="other_charges" id="otherCharges"
                    class="form-control @error('other_charges') is-invalid @enderror"
                    value="{{ old('other_charges', $plotSale?->other_charges) }}" placeholder="Enter other charges">

                @error('other_charges')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Final Payable --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Final Payable
                </label>

                <input type="text" name="final_payable" id="finalPayable" class="form-control" readonly
                    value="{{ old('final_payable', $plotSale?->final_payable) }}"
                    placeholder="Auto calculated final amount">

            </div>


            {{-- Coupon Discount --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Coupon Discount
                </label>

                <input type="text" name="coupon_discount" id="couponDiscount"
                    class="form-control @error('coupon_discount') is-invalid @enderror"
                    value="{{ old('coupon_discount', $plotSale?->coupon_discount) }}"
                    placeholder="Enter coupon discount">

                @error('coupon_discount')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Total Plot Cost --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Total Plot Cost
                </label>

                <input type="text" name="total_plot_cost" id="totalPlotCost" class="form-control" readonly
                    value="{{ old('total_plot_cost', $plotSale?->total_plot_cost) }}"
                    placeholder="Auto calculated total amount">

            </div>


            {{-- Booking Date --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Booking Date
                </label>

                <input type="date" name="booking_date"
                    class="form-control @error('booking_date') is-invalid @enderror"
                    value="{{ old('booking_date', $plotSale?->booking_date) }}">

                @error('booking_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Remark --}}
            <div class="col-md-12 mb-3">

                <label class="form-label fw-semibold">
                    Remark
                </label>

                <textarea name="remark" rows="3" class="form-control @error('remark') is-invalid @enderror"
                    placeholder="Enter booking remark">{{ old('remark', $plotSale?->remark) }}</textarea>

                @error('remark')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

    </div>

</div>
