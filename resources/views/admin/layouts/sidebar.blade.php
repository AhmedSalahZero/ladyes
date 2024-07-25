


<div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

			@foreach(getSidebars($user) as $name => $sidebarItem)
			@if($sidebarItem['show'] && count($sidebarItem['subitems']))
            <li class=" nav-item {{ $sidebarItem['is_active'] ? 'active' : '' }}">
                <x-sidebar.nav-item :route="$sidebarItem['route']" :icon="$sidebarItem['icon']" :title="$sidebarItem['title']" ></x-sidebar.nav-item>
                <ul class="menu-content">
					@foreach($sidebarItem['subitems'] as $sidebarSubitem)
                    <li class="{{ $sidebarSubitem['is_active'] ? 'active' :'' }}">
					<a class="menu-item" href="{{ $sidebarSubitem['route'] }}" >{{ $sidebarSubitem['title'] }}</a>
                    </li>
					@endforeach
					{{-- endforeach sub --}}
                   
                </ul>
            </li>
	
			@elseif($sidebarItem['show'])
			{{-- else --}}
            <li class="nav-item {{ $sidebarItem['is_active'] ? 'active':'' }}">
                <x-sidebar.nav-item :route="$sidebarItem['route']" :icon="$sidebarItem['icon']" :title="$sidebarItem['title']" ></x-sidebar.nav-item>
            </li>
			@endif 
			@endforeach 
           {{-- endif  --}}
        </ul>
    </div>
</div>
