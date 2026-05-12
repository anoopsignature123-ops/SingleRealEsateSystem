
<aside class="app-sidebar bg-success shadow" data-bs-theme="dark">
    <div class="sidebar-brand">

        <a href="#" class="brand-link">

            <img src="{{ asset('assets/images/AdminLTELogo.png') }}" alt="Logo"
                class="brand-image opacity-75 shadow" />

            <span class="brand-text fw-light">
                Admin
            </span>
        </a>
    </div>


    @php

        $user = auth()->user();

        $menus = App\Models\Module::whereNull('parent_id')->with('children')->orderBy('sort_order')->get();

    @endphp


    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false">

                @foreach ($menus as $menu)

                    {{-- Parent Menu --}}
                    @if ($menu->children->count() > 0)
                        @php

                            $visibleChildren = $menu->children->filter(function ($child) use ($user) {
                                return $user->can($child->slug . '-list');
                            });

                            $parentActive = $visibleChildren->contains(function ($child) {
                                return request()->routeIs(...explode(',', $child->active_routes));
                            });

                        @endphp


                        @if ($visibleChildren->count() > 0)
                            <li class="nav-item {{ $parentActive ? 'menu-open' : '' }}">

                                <a href="#" class="nav-link {{ $parentActive ? 'active' : '' }}">

                                    <i class="nav-icon {{ $menu->icon }}"></i>

                                    <p>

                                        {{ $menu->name }}

                                        <i class="nav-arrow bi bi-chevron-right"></i>

                                    </p>

                                </a>


                                <ul class="nav nav-treeview">

                                    @foreach ($visibleChildren as $child)
                                        <li class="nav-item">

                                            <a href="{{ $child->route_name && Route::has($child->route_name) ? route($child->route_name) : '#' }}"
                                                class="nav-link {{ request()->routeIs(...explode(',', $child->active_routes)) ? 'active' : '' }}">

                                                <i class="nav-icon {{ $child->icon }}"></i>

                                                <p>

                                                    {{ $child->name }}

                                                </p>

                                            </a>

                                        </li>
                                    @endforeach

                                </ul>

                            </li>
                        @endif
                    @else
                        {{-- Single Menu --}}
                        @if ($user->can($menu->slug . '-list'))
                            <li class="nav-item">

                                <a href="{{ $menu->route_name && Route::has($menu->route_name) ? route($menu->route_name) : '#' }}"
                                    class="nav-link {{ request()->routeIs(...explode(',', $menu->active_routes)) ? 'active' : '' }}">

                                    <i class="nav-icon {{ $menu->icon }}"></i>

                                    <p>

                                        {{ $menu->name }}

                                    </p>

                                </a>

                            </li>
                        @endif
                    @endif

                @endforeach

            </ul>

        </nav>

    </div>

</aside>

