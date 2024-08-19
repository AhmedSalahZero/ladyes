@props([
	'route',
	'icon',
	'title'
])
<a href="{{ $route }}">

                    <i class="{{ $icon }}"></i>
                    <span class="menu-title" >{{ $title }}</span>
                </a>
