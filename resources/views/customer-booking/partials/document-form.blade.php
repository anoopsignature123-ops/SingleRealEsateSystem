@php

    $prefix = $prefix ?? '';
    $title = $title ?? 'Documents';
    $document = $document ?? null;

@endphp


<div class="card border-0 shadow-sm mb-4">

    <div class="card-body p-4">

        <h5 class="fw-bold border-bottom pb-3 mb-4">
            {{ $title }}
        </h5>


        <div class="row g-4">


            {{-- Driving License --}}
            <div class="col-md-6">

                <div class="border rounded p-3 bg-light">

                    <div class="form-check mb-3">

                        <input type="checkbox" class="form-check-input doc-check" data-target="{{ $prefix }}dlBox"
                            name="{{ $prefix }}dl" value="1"
                            {{ old($prefix . 'dl', $document?->dl) ? 'checked' : '' }}>

                        <label class="form-check-label fw-semibold">
                            Driving License
                        </label>

                    </div>


                    <div id="{{ $prefix }}dlBox"
                        style="{{ old($prefix . 'dl', $document?->dl) ? '' : 'display:none' }}">

                        <input type="file" name="{{ $prefix }}dl_file" class="form-control">

                        @error($prefix . 'dl_file')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror


                        @if ($document?->dl_file)
                            <a href="{{ getFileUrl($document->dl_file) }}" target="_blank"
                                class="btn btn-sm btn-primary mt-2">

                                View File

                            </a>
                        @endif

                    </div>

                </div>

            </div>



            {{-- Aadhar --}}
            <div class="col-md-6">

                <div class="border rounded p-3 bg-light">

                    <div class="form-check mb-3">

                        <input type="checkbox" class="form-check-input doc-check"
                            data-target="{{ $prefix }}aadharBox" name="{{ $prefix }}aadhar" value="1"
                            {{ old($prefix . 'aadhar', $document?->aadhar) ? 'checked' : '' }}>

                        <label class="form-check-label fw-semibold">
                            Aadhar Card
                        </label>

                    </div>


                    <div id="{{ $prefix }}aadharBox"
                        style="{{ old($prefix . 'aadhar', $document?->aadhar) ? '' : 'display:none' }}">

                        <input type="file" name="{{ $prefix }}aadhar_file" class="form-control">

                        @error($prefix . 'aadhar_file')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror


                        @if ($document?->aadhar_file)
                            <a href="{{ getFileUrl($document->aadhar_file) }}" target="_blank"
                                class="btn btn-sm btn-primary mt-2">

                                View File

                            </a>
                        @endif

                    </div>

                </div>

            </div>



            {{-- Voter ID --}}
            <div class="col-md-6">

                <div class="border rounded p-3 bg-light">

                    <div class="form-check mb-3">

                        <input type="checkbox" class="form-check-input doc-check"
                            data-target="{{ $prefix }}voterBox" name="{{ $prefix }}voter_id"
                            value="1" {{ old($prefix . 'voter_id', $document?->voter_id) ? 'checked' : '' }}>

                        <label class="form-check-label fw-semibold">
                            Voter ID
                        </label>

                    </div>


                    <div id="{{ $prefix }}voterBox"
                        style="{{ old($prefix . 'voter_id', $document?->voter_id) ? '' : 'display:none' }}">

                        <input type="file" name="{{ $prefix }}voter_id_file" class="form-control">

                        @error($prefix . 'voter_id_file')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror


                        @if ($document?->voter_id_file)
                            <a href="{{ getFileUrl($document->voter_id_file) }}" target="_blank"
                                class="btn btn-sm btn-primary mt-2">

                                View File

                            </a>
                        @endif

                    </div>

                </div>

            </div>



            {{-- Other --}}
            <div class="col-md-6">

                <div class="border rounded p-3 bg-light">

                    <div class="form-check mb-3">

                        <input type="checkbox" class="form-check-input doc-check"
                            data-target="{{ $prefix }}otherBox" name="{{ $prefix }}other" value="1"
                            {{ old($prefix . 'other', $document?->other) ? 'checked' : '' }}>

                        <label class="form-check-label fw-semibold">
                            Other Document
                        </label>

                    </div>


                    <div id="{{ $prefix }}otherBox"
                        style="{{ old($prefix . 'other', $document?->other) ? '' : 'display:none' }}">

                        <input type="file" name="{{ $prefix }}other_file" class="form-control">

                        @error($prefix . 'other_file')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror


                        @if ($document?->other_file)
                            <a href="{{ getFileUrl($document->other_file) }}" target="_blank"
                                class="btn btn-sm btn-primary mt-2">

                                View File

                            </a>
                        @endif

                    </div>

                </div>

            </div>



            {{-- Profile Picture --}}
            <div class="col-md-12">

                <div class="border rounded p-3 bg-light">

                    <div class="form-check mb-3">

                        <input type="checkbox" class="form-check-input doc-check"
                            data-target="{{ $prefix }}profileBox" name="{{ $prefix }}profile_enabled"
                            value="1"
                            {{ old($prefix . 'profile_enabled', $document?->profile_enabled) ? 'checked' : '' }}>

                        <label class="form-check-label fw-semibold">
                            Profile Picture
                        </label>

                    </div>


                    <div id="{{ $prefix }}profileBox"
                        style="{{ old($prefix . 'profile_enabled', $document?->profile_enabled) ? '' : 'display:none' }}">

                        <input type="file" name="{{ $prefix }}profile_picture" class="form-control">

                        @error($prefix . 'profile_picture')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror


                        @if ($document?->profile_picture)
                            <img src="{{ getFileUrl($document->profile_picture) }}" width="100"
                                class="mt-2 rounded border">
                        @endif

                    </div>

                </div>

            </div>


        </div>

    </div>

</div>
