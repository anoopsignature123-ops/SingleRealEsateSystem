<div class="org-level">

    {{-- Tooltip card ke bahar --}}
    <div class="node-wrapper">

        <div class="associate-tooltip">

            <div class="tooltip-header">

                <div class="tooltip-title">
                    {{ $associate->associate_name }}
                </div>

                <div class="tooltip-subtitle">
                    {{ $associate->associate_id }}
                </div>

                <div class="tooltip-date">
                    Joining Date: {{ $associate->created_at?->format('d-m-Y') }}
                </div>
            </div>
            <div class="tooltip-body">
                <p>
                    <strong>Sponsor ID:</strong>
                    {{ $associate->sponsor_id ?? '-' }}
                </p>
                <p>
                    <strong>Under Place:</strong>
                    {{ $associate->under_place_id ?? 'N/A' }}
                </p>
                <p>
                    <strong>Direct Associate :</strong>
                    {{ $associate->direct_count }}
                </p>

                <p>
                    <strong>Associate Downline :</strong>
                    {{ $associate->downline_count }}
                </p>

                <p>
                    <strong>Level :</strong>
                    {{ $associate->level }}
                </p>
                <p>
                    <strong>Mobile:</strong>
                    {{ $associate->mobile_number ?? '-' }}
                </p>

                <p>
                    <strong>Rank:</strong>
                    {{ $associate->rank?->designation ?? '-' }}
                </p>

                <p>
                    <strong>Joining:</strong>
                    {{ $associate->created_at?->format('d-m-Y') }}
                </p>

            </div>
        </div>
        {{-- Main Card --}}
        <div class="associate-card">

            <div class="avatar-circle">
                <i class="bi bi-person-fill"></i>
            </div>

            <div class="associate-code">
                {{ $associate->associate_id }}
            </div>

            <div class="associate-name">
                {{ $associate->associate_name }}
            </div>

        </div>

    </div>


    {{-- Child Nodes --}}
    @if ($associate->children->count())

        <div class="vertical-line"></div>

        <div class="children-wrapper">

            @foreach ($associate->children as $child)
                <div class="child-node">

                    @include('associate-tree.node', [
                        'associate' => $child,
                    ])

                </div>
            @endforeach

        </div>

    @endif

</div>
