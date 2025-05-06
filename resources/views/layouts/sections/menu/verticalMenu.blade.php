<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    <div class="app-brand justify-content-center demo">
        <a href="{{url('/')}}" class="logo d-flex align-items-center me-auto me-xl-0 order-first">
            <img src="{{ asset('landing_page_rss/teknikgeo.png') }}" alt="">
            <h1 class="sitename" style="font-size: 22px;">{{ config('variables.templateName') }}</h1>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $key => $menu)

            {{-- adding active and open class if child is active --}}

            {{-- menu headers --}}
            @if (isset($menu->menuHeader))
                @if ($key != 0)
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
                    </li>
                @endif
            @else

                {{-- active menu method --}}
                @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();
                    $listSlug = explode(',', $menu->slug);


                    if ($currentRouteName === $menu->slug) {
                      $activeClass = 'active';
                    }
                    elseif (isset($menu->submenu)) {
                      if (count($listSlug) > 0) {
                        foreach($listSlug as $slug){
                          if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
                            $activeClass = 'active open';
                          }
                        }
                      }
                      else{
                        if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
                          $activeClass = 'active open';
                        }
                      }
                    }else{
                        if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
                          $activeClass = 'active open';
                        }
                    }
                @endphp

                {{-- main menu --}}
                <li class="menu-item {{$activeClass}}">
                    <a href="{{ isset($menu->url) ? route($menu->url) : 'javascript:void(0);' }}"
                       class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                       @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                        @isset($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @endisset
                        <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                        @isset($menu->badge)
                            <div
                                class="badge rounded-pill bg-{{ $menu->badge[0] }} text-uppercase ms-auto">{{ $menu->badge[1] }}</div>
                        @endisset
                    </a>

                    {{-- submenu --}}
                    @isset($menu->submenu)
                        @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
                    @endisset
                </li>
            @endif
        @endforeach
    </ul>

</aside>
