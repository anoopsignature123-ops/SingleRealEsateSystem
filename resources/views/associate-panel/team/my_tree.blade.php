@extends('layouts.app')

@push('title') Associate Panel | My Tree @endpush
@php
    $associateUser = auth('associate')->user();
    $buildTreeData = function ($associate, $isRoot = false) use (&$buildTreeData) {
        if (!$associate) { return null; }
        $treeStats = $associate->tree_stats ?? [];
        $children = [];

        foreach (collect($associate->children ?? []) as $child) {
            $childTreeData = $buildTreeData($child, false);
            if ($childTreeData) { $children[] = $childTreeData; }
        }

        $associateName = trim((string) ($associate->associate_name ?? 'Associate'));
        $rankName = trim((string) ($associate->rank?->designation ?? 'Associate'));

        return [
            'id' => (int) $associate->id,
            'associate_id' => $associate->associate_id ?? '-',
            'name' => $associateName,
            'initial' => strtoupper(mb_substr($associateName ?: 'A', 0, 1)),
            'is_root' => $isRoot,
            'node_type' => $isRoot ? 'root' : 'associate',
            'rank' => $rankName,
            'sponsor_id' => $associate->sponsor_id ?? '-',
            'under_place_id' => $associate->under_place_id ?? '-',
            'mobile' => $associate->mobile_number ?? '-',
            'level' => (int) ($associate->level ?? 0),
            'joining_date' => $associate->created_at?->format('d M Y') ?? '-',
            'direct_count' => (int) ($treeStats['direct_count'] ?? collect($associate->children ?? [])->count()),
            'downline_count' => (int) ($treeStats['downline_count'] ?? 0),
            'self_business' => (float) ($treeStats['self_business'] ?? 0),
            'team_business' => (float) ($treeStats['team_business'] ?? 0),
            'total_business' => (float) ($treeStats['total_business'] ?? 0),
            'self_area' => (float) ($treeStats['plot_area'] ?? 0),
            'team_area' => (float) ($treeStats['team_area'] ?? 0),
            'total_area' => (float) ($treeStats['total_area'] ?? 0),
            'children' => $children,
        ];
    };

    $treeData = $rootAssociate ? $buildTreeData($rootAssociate, true) : null;
    $rootStats = $rootAssociate?->tree_stats ?? [];
@endphp

@section('content')
    <div class="container-fluid mt-4 associate-tree-page">
        <div class="tree-page-header mb-4">
            <div class="tree-page-title">
                <div class="tree-title-icon"> <i class="bi bi-diagram-3"></i> </div>
                <div>
                    <span class="tree-kicker">Associate Network</span>
                    <h3>My Tree View</h3>
                    <p>View your team hierarchy, direct members and complete downline structure.</p>
                </div>
            </div>

            <form action="javascript:void(0)" class="tree-search-form" id="associateTreeFilterForm" autocomplete="off">
                <div class="tree-filter-field">
                    <label for="associateTreeSearch">Associate ID / Name</label>
                    <input type="text" id="associateTreeSearch" class="form-control"
                        placeholder="Search downline associate ID or name" autocomplete="off">
                </div>

                <div class="tree-filter-actions">
                    <button type="button" class="btn btn-success" id="applyTreeFilter">
                        <i class="bi bi-search"></i> Show
                    </button>

                    <button type="button" class="btn btn-outline-secondary" id="resetTreeFilter">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>

                    @if ($rootAssociate)
                        <button type="button" id="downloadTree" class="btn btn-dark">
                            <i class="bi bi-download"></i>
                            <span id="downloadButtonText">Download Tree</span>
                            <span id="downloadSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <div class="tree-main-card">
            <div class="tree-card-head">
                <div>
                    <h5 class="fw-bold mb-1" id="treeChartTitle">Team Hierarchy</h5>
                    <small class="text-muted" id="treeFilterStatus">
                        Drag to move the chart and hover over a node for details.
                    </small>
                </div>

                @if ($rootAssociate)
                    <div class="tree-summary-pills">
                        <span>
                            <i class="bi bi-person-plus me-1"></i>
                            <strong id="visibleTreeCount" class="me-1">{{ $rootStats['downline_count'] ?? 0 }}</strong>
                            Visible Associates
                        </span>

                        <span>
                            <i class="bi bi-person-check me-1"></i>
                            <strong class="me-1">
                                {{ $rootStats['direct_count'] ?? collect($rootAssociate->children ?? [])->count() }}
                            </strong>
                            Direct Associates
                        </span>

                        <span>
                            <i class="bi bi-people me-1"></i>
                            <strong class="me-1">{{ $rootStats['downline_count'] ?? 0 }}</strong>
                            Total Downline
                        </span>

                        <span>
                            <i class="bi bi-cash-stack me-1"></i>
                            <strong class="me-1">
                                Rs. {{ number_format((float) ($rootStats['total_business'] ?? 0), 2) }}
                            </strong>
                            Paid Business
                        </span>
                    </div>
                @endif
            </div>

            @if ($rootAssociate)
                <div class="compact-tree-scroll" id="treeScrollArea">
                    <div id="treeFilterLoader" class="tree-filter-loader d-none">
                        <div class="tree-filter-loader-box">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading Tree...
                        </div>
                    </div>

                    <div class="compact-tree-scroll-inner">
                        <div class="compact-tree-export" id="treeExportContainer">
                            <div class="tree-download-heading" id="treeDownloadHeading">
                                <div>
                                    <h4>Associate Network Tree</h4>
                                    <p id="treeDownloadFilterText">
                                        Root: {{ $rootAssociate->associate_name ?? '-' }}
                                        ({{ $rootAssociate->associate_id ?? '-' }})
                                    </p>
                                </div>

                                <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>
                            </div>

                            <svg id="associateTreeSvg" xmlns="http://www.w3.org/2000/svg"></svg>
                        </div>
                    </div>
                </div>

                <div id="treeNodeTooltip" class="compact-tree-tooltip"></div>
            @else
                <div class="tree-empty-box">
                    <div class="tree-empty-icon">
                        <i class="bi bi-diagram-3"></i>
                    </div>

                    <h5 class="fw-bold mb-1">No Associate Found</h5>
                    <p class="text-muted mb-0">Search with your own associate ID or a valid downline associate ID.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@include('treeScript')
