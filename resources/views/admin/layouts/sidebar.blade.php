<div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
    <div class="main-menu-content">
        <?php $c_url = url()->current();
            $path_url = explode(url('/').'/',$c_url);
        ?>
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <?php $links = \App\Models\Links::where('is_sidebar', 1)
                ->where('is_sub_menue', 0)->orderBy('sort_id')->get();
            ?>
            @if($links && count($links) > 0)
                @foreach($links as $link)
                    @if(admin()->check_route_permission($link->route_name) == 1)
                        @if(isset($link->sub_links) && count($link->sub_links) > 0)

                            <li class=" nav-item">
                                <a href="#">
                                    <i class="{{$link->icon}}"></i>
                                    <span class="menu-title"
                                          data-i18n="nav.dash.main">{{$link->name}}</span>
                                </a>
                                <ul class="menu-content">
                                    @foreach($link->sub_links as $item)
                                        @if(admin()->check_route_permission($link->route_name) == 1)
                                            <li class="{{isset($path_url[1]) && $path_url[1] == $item->utl_path ? 'active' : ''}}"><a class="menu-item" href="{{route($item->route_name)}}"
                                                   data-i18n="nav.dash.ecommerce">{{$item->name}}</a>

                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item {{isset($path_url[1]) && $path_url[1] == $link->utl_path ? 'active' : ''}}

                            "><a href="{{route($link->route_name)}}">
                                    <i class="{{$link->icon}}"></i>
                                    <span class="menu-title">{{$link->name}}</span></a>
                            </li>
                        @endif
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>
