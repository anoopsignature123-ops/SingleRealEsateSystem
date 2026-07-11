@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/d3@7/dist/d3.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.min.js"></script>

    <script>
        window.associateTreeConfig = {
            treeData: @json($treeData),
            rootAssociateId: @json($rootAssociate?->associate_id ?? 'root'),
            rootAssociateName: @json($rootAssociate?->associate_name ?? 'Root'),
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            'use strict';

            const config =
                window.associateTreeConfig || {};

            if (!config.treeData) {
                return;
            }

            const svgElement =
                document.getElementById(
                    'associateTreeSvg'
                );

            const exportContainer =
                document.getElementById(
                    'treeExportContainer'
                );

            const scrollArea =
                document.getElementById(
                    'treeScrollArea'
                );

            const tooltip =
                document.getElementById(
                    'treeNodeTooltip'
                );

            const searchInput =
                document.getElementById(
                    'associateTreeSearch'
                );

            const applyFilterButton =
                document.getElementById(
                    'applyTreeFilter'
                );

            const resetFilterButton =
                document.getElementById(
                    'resetTreeFilter'
                );

            const visibleTreeCount =
                document.getElementById(
                    'visibleTreeCount'
                );

            const filterStatus =
                document.getElementById(
                    'treeFilterStatus'
                );

            const treeChartTitle =
                document.getElementById(
                    'treeChartTitle'
                );

            const filterLoader =
                document.getElementById(
                    'treeFilterLoader'
                );

            const downloadFilterText =
                document.getElementById(
                    'treeDownloadFilterText'
                );

            const downloadButton =
                document.getElementById(
                    'downloadTree'
                );

            if (
                !svgElement ||
                !exportContainer ||
                !scrollArea ||
                !tooltip
            ) {
                return;
            }

            function cloneTreeData(data) {
                return JSON.parse(
                    JSON.stringify(data)
                );
            }

            function normalizeValue(value) {
                return String(value ?? '')
                    .trim()
                    .toLowerCase();
            }

            const originalTreeData =
                cloneTreeData(config.treeData);

            let currentTreeData =
                cloneTreeData(originalTreeData);

            let currentSvgWidth = 0;
            let currentSvgHeight = 0;

            let searchTimer = null;
            let filterTimer = null;
            let filterRequestId = 0;

            const nodeRadius = 64;
            const rootRadius = 71;
            const horizontalGap = 195;
            const verticalGap = 200;

            const outerPaddingX = 90;
            const outerPaddingTop = 80;
            const outerPaddingBottom = 95;

            function nodeMatchesSearch(
                node,
                searchValue
            ) {
                if (!node) {
                    return false;
                }

                const associateId =
                    normalizeValue(
                        node.associate_id
                    );

                const associateName =
                    normalizeValue(node.name);

                return searchValue === '' ||
                    associateId === searchValue ||
                    associateId.includes(searchValue) ||
                    associateName.includes(searchValue);
            }

            function findAssociateNode(
                node,
                searchValue
            ) {
                if (!node) {
                    return null;
                }

                if (
                    nodeMatchesSearch(
                        node,
                        searchValue
                    )
                ) {
                    return node;
                }

                for (
                    const child of
                        (node.children || [])
                ) {
                    const matchedNode =
                        findAssociateNode(
                            child,
                            searchValue
                        );

                    if (matchedNode) {
                        return matchedNode;
                    }
                }

                return null;
            }

            function getFilteredTreeData() {
                const searchValue =
                    normalizeValue(
                        searchInput?.value
                    );

                if (searchValue === '') {
                    return cloneTreeData(
                        originalTreeData
                    );
                }

                const matchedNode =
                    findAssociateNode(
                        originalTreeData,
                        searchValue
                    );

                if (!matchedNode) {
                    return null;
                }

                const clonedNode =
                    cloneTreeData(matchedNode);

                clonedNode.is_root = true;
                clonedNode.node_type = 'root';

                return clonedNode;
            }

            function countVisibleAssociates(
                treeData
            ) {
                if (!treeData) {
                    return 0;
                }

                let count = 0;

                function countNodes(
                    node,
                    isRoot = false
                ) {
                    if (!isRoot) {
                        count++;
                    }

                    (
                        node.children || []
                    ).forEach(function(child) {
                        countNodes(
                            child,
                            false
                        );
                    });
                }

                countNodes(treeData, true);

                return count;
            }

            function updateFilterStatus(
                treeData
            ) {
                const searchValue =
                    String(
                        searchInput?.value || ''
                    ).trim();

                const count =
                    countVisibleAssociates(
                        treeData
                    );

                const rootName =
                    treeData?.name ||
                    config.rootAssociateName ||
                    'Associate';

                const titleText =
                    `Associate Team - ${rootName}`;

                if (treeChartTitle) {
                    treeChartTitle.textContent =
                        titleText;
                }

                if (visibleTreeCount) {
                    visibleTreeCount.textContent =
                        count;
                }

                if (downloadFilterText) {
                    downloadFilterText.textContent =
                        titleText;
                }

                if (!filterStatus) {
                    return;
                }

                if (!treeData) {
                    filterStatus.textContent =
                        'No matching associate found.';

                    return;
                }

                if (searchValue === '') {
                    filterStatus.textContent =
                        `${titleText}. Drag to move the chart and hover over a node for details.`;

                    return;
                }

                filterStatus.textContent =
                    `${titleText}. ${count} downline associates found.`;
            }

            function clearRenderedTree() {
                tooltip.style.display = 'none';

                while (
                    svgElement.firstChild
                ) {
                    svgElement.removeChild(
                        svgElement.firstChild
                    );
                }

                svgElement.removeAttribute(
                    'width'
                );

                svgElement.removeAttribute(
                    'height'
                );

                svgElement.removeAttribute(
                    'viewBox'
                );
            }

            function moveSubtree(
                node,
                difference
            ) {
                node.each(
                    function(descendant) {
                        descendant.x +=
                            difference;
                    }
                );
            }

            function renderAssociateTree(
                treeData
            ) {
                clearRenderedTree();

                if (!treeData) {
                    currentSvgWidth = 0;
                    currentSvgHeight = 0;

                    updateFilterStatus(null);

                    return;
                }

                const root =
                    d3.hierarchy(treeData);

                const treeLayout = d3
                    .tree()
                    .nodeSize([
                        horizontalGap,
                        verticalGap,
                    ])
                    .separation(
                        function(
                            nodeA,
                            nodeB
                        ) {
                            return nodeA.parent ===
                                nodeB.parent ?
                                1.15 :
                                1.4;
                        }
                    );

                treeLayout(root);

                const nodesByDepth =
                    d3.group(
                        root.descendants(),
                        function(node) {
                            return node.depth;
                        }
                    );

                const minimumNodeDistance =
                    nodeRadius * 2 + 42;

                nodesByDepth.forEach(
                    function(levelNodes) {
                        levelNodes.sort(
                            function(
                                nodeA,
                                nodeB
                            ) {
                                return (
                                    nodeA.x -
                                    nodeB.x
                                );
                            }
                        );

                        for (
                            let index = 1; index <
                            levelNodes.length; index++
                        ) {
                            const previousNode =
                                levelNodes[
                                    index - 1
                                ];

                            const currentNode =
                                levelNodes[index];

                            const currentDistance =
                                currentNode.x -
                                previousNode.x;

                            if (
                                currentDistance >=
                                minimumNodeDistance
                            ) {
                                continue;
                            }

                            moveSubtree(
                                currentNode,
                                minimumNodeDistance -
                                currentDistance
                            );
                        }
                    }
                );

                const allNodes =
                    root.descendants();

                const minX = d3.min(
                    allNodes,
                    function(node) {
                        const radius =
                            node.depth === 0 ?
                            rootRadius :
                            nodeRadius;

                        return node.x - radius;
                    }
                );

                const maxX = d3.max(
                    allNodes,
                    function(node) {
                        const radius =
                            node.depth === 0 ?
                            rootRadius :
                            nodeRadius;

                        return node.x + radius;
                    }
                );

                const maximumY = d3.max(
                    allNodes,
                    function(node) {
                        const radius =
                            node.depth === 0 ?
                            rootRadius :
                            nodeRadius;

                        return node.y + radius;
                    }
                );

                const svgWidth = Math.ceil(
                    maxX -
                    minX +
                    outerPaddingX * 2
                );

                const svgHeight = Math.ceil(
                    maximumY +
                    outerPaddingTop +
                    outerPaddingBottom
                );

                currentSvgWidth = svgWidth;
                currentSvgHeight = svgHeight;

                const xOffset =
                    outerPaddingX - minX;

                const yOffset =
                    outerPaddingTop;

                const svg = d3
                    .select(svgElement)
                    .attr(
                        'width',
                        svgWidth
                    )
                    .attr(
                        'height',
                        svgHeight
                    )
                    .attr(
                        'viewBox',
                        `0 0 ${svgWidth} ${svgHeight}`
                    );

                const chart = svg
                    .append('g')
                    .attr(
                        'transform',
                        `translate(${xOffset}, ${yOffset})`
                    );

                chart
                    .append('g')
                    .attr(
                        'class',
                        'tree-links-layer'
                    )
                    .selectAll('path')
                    .data(root.links())
                    .join('path')
                    .attr(
                        'class',
                        'tree-link'
                    )
                    .attr(
                        'd',
                        function(link) {
                            const sourceRadius =
                                link.source
                                .depth === 0 ?
                                rootRadius :
                                nodeRadius;

                            const sourceX =
                                link.source.x;

                            const sourceY =
                                link.source.y +
                                sourceRadius;

                            const targetX =
                                link.target.x;

                            const targetY =
                                link.target.y -
                                nodeRadius;

                            const middleY =
                                Math.round(
                                    sourceY +
                                    (
                                        targetY -
                                        sourceY
                                    ) / 2
                                );

                            return [
                                `M ${sourceX} ${sourceY}`,
                                `V ${middleY}`,
                                `H ${targetX}`,
                                `V ${targetY}`,
                            ].join(' ');
                        }
                    );

                const nodes = chart
                    .append('g')
                    .attr(
                        'class',
                        'tree-nodes-layer'
                    )
                    .selectAll('g')
                    .data(allNodes)
                    .join('g')
                    .attr(
                        'class',
                        function(node) {
                            return node.depth === 0 ?
                                'svg-tree-node root' :
                                'svg-tree-node associate';
                        }
                    )
                    .attr(
                        'transform',
                        function(node) {
                            return `translate(${node.x}, ${node.y})`;
                        }
                    );

                nodes
                    .append('circle')
                    .attr(
                        'class',
                        'svg-tree-node-circle'
                    )
                    .attr(
                        'r',
                        function(node) {
                            return node.depth === 0 ?
                                rootRadius :
                                nodeRadius;
                        }
                    );

                nodes
                    .append('circle')
                    .attr(
                        'class',
                        'svg-avatar-circle'
                    )
                    .attr('cy', -28)
                    .attr(
                        'r',
                        function(node) {
                            return node.depth === 0 ?
                                25 :
                                22;
                        }
                    );

                nodes
                    .append('text')
                    .attr(
                        'class',
                        'svg-avatar-text'
                    )
                    .attr('y', -28)
                    .text(
                        function(node) {
                            return (
                                node.data.initial
                            );
                        }
                    );

                nodes
                    .append('text')
                    .attr(
                        'class',
                        'svg-node-id'
                    )
                    .attr('y', 10)
                    .text(
                        function(node) {
                            return (
                                node.data
                                .associate_id
                            );
                        }
                    );

                nodes
                    .append('text')
                    .attr(
                        'class',
                        'svg-node-name'
                    )
                    .attr('y', 26)
                    .text(
                        function(node) {
                            const name =
                                String(
                                    node.data
                                    .name || ''
                                );

                            return name.length > 16 ?
                                name.substring(
                                    0,
                                    16
                                ) + '...' :
                                name;
                        }
                    );

                nodes
                    .append('rect')
                    .attr(
                        'class',
                        'svg-rank-badge'
                    )
                    .attr('x', -43)
                    .attr('y', 39)
                    .attr('width', 86)
                    .attr('height', 19)
                    .attr('rx', 9.5);

                nodes
                    .append('text')
                    .attr(
                        'class',
                        'svg-node-rank'
                    )
                    .attr('y', 48.5)
                    .text(
                        function(node) {
                            const rank =
                                String(
                                    node.data
                                    .rank ||
                                    'Associate'
                                );

                            return rank.length > 16 ?
                                rank.substring(
                                    0,
                                    16
                                ) + '...' :
                                rank;
                        }
                    );

                bindTooltipEvents(nodes);
                updateFilterStatus(treeData);

                requestAnimationFrame(
                    function() {
                        scrollArea.scrollLeft =
                            Math.max(
                                0,
                                (
                                    scrollArea
                                    .scrollWidth -
                                    scrollArea
                                    .clientWidth
                                ) / 2
                            );

                        scrollArea.scrollTop = 0;
                    }
                );
            }

            function escapeHtml(value) {
                const temporaryElement =
                    document.createElement(
                        'div'
                    );

                temporaryElement.textContent =
                    String(value ?? '-');

                return temporaryElement.innerHTML;
            }

            function formatMoney(value) {
                return 'Rs. ' +
                    Number(value || 0)
                    .toLocaleString(
                        'en-IN', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }
                    );
            }

            function formatArea(value) {
                return Number(value || 0)
                    .toLocaleString(
                        'en-IN', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }
                    ) + ' Sqft';
            }

            function tooltipRow(
                label,
                value
            ) {
                return `
                    <div class="compact-tooltip-row">
                        <span>${escapeHtml(label)}</span>
                        <strong>${escapeHtml(value)}</strong>
                    </div>
                `;
            }

            function moveTooltip(event) {
                const tooltipWidth = 280;

                const tooltipHeight =
                    tooltip.offsetHeight || 420;

                let left =
                    event.clientX + 16;

                let top =
                    event.clientY + 16;

                if (
                    left + tooltipWidth >
                    window.innerWidth - 15
                ) {
                    left =
                        event.clientX -
                        tooltipWidth -
                        16;
                }

                if (
                    top + tooltipHeight >
                    window.innerHeight - 15
                ) {
                    top =
                        window.innerHeight -
                        tooltipHeight -
                        15;
                }

                tooltip.style.left =
                    `${Math.max(15, left)}px`;

                tooltip.style.top =
                    `${Math.max(15, top)}px`;
            }

            function bindTooltipEvents(
                nodes
            ) {
                nodes
                    .on(
                        'mouseenter',
                        function(
                            event,
                            node
                        ) {
                            tooltip.innerHTML = `
                                <div class="compact-tooltip-header">
                                    <div class="compact-tooltip-avatar">
                                        ${escapeHtml(node.data.initial)}
                                    </div>

                                    <div>
                                        <div class="compact-tooltip-name">
                                            ${escapeHtml(node.data.name)}
                                        </div>

                                        <div class="compact-tooltip-id">
                                            ${escapeHtml(node.data.associate_id)}
                                        </div>
                                    </div>
                                </div>

                                <div class="compact-tooltip-body">
                                    ${tooltipRow(
                                        'Rank',
                                        node.data.rank
                                    )}

                                    ${tooltipRow(
                                        'Sponsor ID',
                                        node.data.sponsor_id
                                    )}

                                    ${tooltipRow(
                                        'Under Place',
                                        node.data.under_place_id
                                    )}

                                    ${tooltipRow(
                                        'Level',
                                        node.data.level
                                    )}

                                    ${tooltipRow(
                                        'Direct Team',
                                        node.data.direct_count
                                    )}

                                    ${tooltipRow(
                                        'Total Downline',
                                        node.data.downline_count
                                    )}

                                    ${tooltipRow(
                                        'Self Plot Area',
                                        formatArea(
                                            node.data.self_area
                                        )
                                    )}

                                    ${tooltipRow(
                                        'Team Plot Area',
                                        formatArea(
                                            node.data.team_area
                                        )
                                    )}

                                    ${tooltipRow(
                                        'Total Area',
                                        formatArea(
                                            node.data.total_area
                                        )
                                    )}

                                    ${tooltipRow(
                                        'Self Business',
                                        formatMoney(
                                            node.data.self_business
                                        )
                                    )}

                                    ${tooltipRow(
                                        'Team Business',
                                        formatMoney(
                                            node.data.team_business
                                        )
                                    )}

                                    ${tooltipRow(
                                        'Total Business',
                                        formatMoney(
                                            node.data.total_business
                                        )
                                    )}

                                    ${tooltipRow(
                                        'Mobile',
                                        node.data.mobile
                                    )}

                                    ${tooltipRow(
                                        'Joining Date',
                                        node.data.joining_date
                                    )}
                                </div>
                            `;

                            tooltip.style.display =
                                'block';

                            moveTooltip(event);
                        }
                    )
                    .on(
                        'mousemove',
                        function(event) {
                            moveTooltip(event);
                        }
                    )
                    .on(
                        'mouseleave',
                        function() {
                            tooltip.style.display =
                                'none';
                        }
                    );
            }

            function applyRealtimeTreeFilter() {
                window.clearTimeout(
                    searchTimer
                );

                window.clearTimeout(
                    filterTimer
                );

                const requestId =
                    ++filterRequestId;

                filterLoader?.classList.remove(
                    'd-none'
                );

                filterTimer =
                    window.setTimeout(
                        function() {
                            if (
                                requestId !==
                                filterRequestId
                            ) {
                                return;
                            }

                            const filteredTreeData =
                                getFilteredTreeData();

                            currentTreeData =
                                filteredTreeData ?
                                cloneTreeData(
                                    filteredTreeData
                                ) :
                                null;

                            renderAssociateTree(
                                currentTreeData
                            );

                            filterLoader?.classList.add(
                                'd-none'
                            );

                            filterTimer = null;
                        },
                        160
                    );
            }

            searchInput?.addEventListener(
                'input',
                function() {
                    window.clearTimeout(
                        searchTimer
                    );

                    window.clearTimeout(
                        filterTimer
                    );

                    const requestId =
                        ++filterRequestId;

                    searchTimer =
                        window.setTimeout(
                            function() {
                                if (
                                    requestId !==
                                    filterRequestId
                                ) {
                                    return;
                                }

                                applyRealtimeTreeFilter();
                            },
                            250
                        );
                }
            );

            searchInput?.addEventListener(
                'keydown',
                function(event) {
                    if (
                        event.key !== 'Enter'
                    ) {
                        return;
                    }

                    event.preventDefault();

                    window.clearTimeout(
                        searchTimer
                    );

                    applyRealtimeTreeFilter();
                }
            );

            applyFilterButton?.addEventListener(
                'click',
                function() {
                    window.clearTimeout(
                        searchTimer
                    );

                    applyRealtimeTreeFilter();
                }
            );

            resetFilterButton?.addEventListener(
                'click',
                function(event) {
                    event.preventDefault();

                    window.clearTimeout(
                        searchTimer
                    );

                    window.clearTimeout(
                        filterTimer
                    );

                    filterRequestId++;

                    searchTimer = null;
                    filterTimer = null;

                    if (searchInput) {
                        searchInput.value = '';
                    }

                    filterLoader?.classList.add(
                        'd-none'
                    );

                    currentTreeData =
                        cloneTreeData(
                            originalTreeData
                        );

                    renderAssociateTree(
                        currentTreeData
                    );
                }
            );

            let isDragging = false;
            let dragStartX = 0;
            let dragStartY = 0;

            let originalScrollLeft = 0;
            let originalScrollTop = 0;

            scrollArea.addEventListener(
                'mousedown',
                function(event) {
                    if (
                        event.target.closest(
                            '.svg-tree-node'
                        )
                    ) {
                        return;
                    }

                    isDragging = true;

                    dragStartX =
                        event.pageX;

                    dragStartY =
                        event.pageY;

                    originalScrollLeft =
                        scrollArea.scrollLeft;

                    originalScrollTop =
                        scrollArea.scrollTop;

                    scrollArea.classList.add(
                        'is-dragging'
                    );
                }
            );

            window.addEventListener(
                'mousemove',
                function(event) {
                    if (!isDragging) {
                        return;
                    }

                    event.preventDefault();

                    scrollArea.scrollLeft =
                        originalScrollLeft -
                        (
                            event.pageX -
                            dragStartX
                        );

                    scrollArea.scrollTop =
                        originalScrollTop -
                        (
                            event.pageY -
                            dragStartY
                        );
                }
            );

            window.addEventListener(
                'mouseup',
                function() {
                    isDragging = false;

                    scrollArea.classList.remove(
                        'is-dragging'
                    );
                }
            );

            downloadButton?.addEventListener('click', async function() {
                const spinner = document.getElementById('downloadSpinner');
                const buttonText = document.getElementById('downloadButtonText');
                const downloadHeading = document.getElementById('treeDownloadHeading');
                if (currentSvgWidth <= 0 || currentSvgHeight <= 0) {
                    alert('Download ke liye koi associate nahi mila.');
                    return;
                }
                downloadButton.disabled = true;
                spinner?.classList.remove('d-none');
                if (buttonText) {
                    buttonText.textContent = 'Preparing...';
                }
                tooltip.style.display = 'none';
                exportContainer.classList.add('download-mode');
                try {
                    await document.fonts.ready;
                    await new Promise(
                        function(resolve) {
                            requestAnimationFrame(
                                function() {
                                    requestAnimationFrame(resolve);
                                }
                            );
                        }
                    );
                    const exportWidth = Math.ceil(currentSvgWidth + 60);
                    if (downloadHeading) {
                        downloadHeading.style.width = `${currentSvgWidth}px`;
                    }
                    exportContainer.style.width = `${exportWidth}px`;
                    const exportHeight = Math.ceil(exportContainer.scrollHeight);
                    const imageData =
                        await htmlToImage.toPng(
                            exportContainer, {
                                cacheBust: true,
                                pixelRatio: 2,
                                backgroundColor: '#ffffff',
                                width: exportWidth,
                                height: exportHeight,
                                canvasWidth: exportWidth * 2,
                                canvasHeight: exportHeight * 2,
                                style: {
                                    width: `${exportWidth}px`,
                                    height: `${exportHeight}px`,
                                    minWidth: '0',
                                    maxWidth: 'none',
                                    overflow: 'visible',
                                },
                            }
                        );
                    const safeAssociateId = String(config.rootAssociateId || 'root').replace(
                        /[^a-zA-Z0-9-_]/g, '-');
                    const currentDate = new Date().toISOString().slice(0, 10);
                    const downloadLink = document.createElement('a');
                    downloadLink.download = `associate-tree-${safeAssociateId}-${currentDate}.png`;
                    downloadLink.href = imageData;
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    downloadLink.remove();
                } catch (error) {
                    console.error('Tree download error:', error);
                    alert('Tree image download nahi ho payi. Page refresh karke dobara try karein.');
                } finally {
                    exportContainer.classList.remove('download-mode');
                    exportContainer.style.width = '';
                    if (downloadHeading) {
                        downloadHeading.style.width = '';
                    }
                    downloadButton.disabled = false;
                    spinner?.classList.add('d-none');

                    if (buttonText) {
                        buttonText.textContent = 'Download Tree';
                    }
                }
            });
            renderAssociateTree(currentTreeData);
        });
    </script>
@endpush
