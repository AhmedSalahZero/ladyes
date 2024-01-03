<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item">
                    <a class="navbar-brand" href="{{route('dashboard.index')}}">
                        <img class="brand-logo" alt="modern admin logo"
                             src="{{asset('assets/images/logo.png')}}">
                        <h3 class="brand-text">{{__('msg.site_name')}}</h3>
                    </a>
                </li>
                <li class="nav-item d-md-none">
                    <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content" style="background-color: #ff7a7f !important;">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
                    <li class="nav-item nav-search"><a class="nav-link nav-link-search" href="#"><i class="ficon ft-search"></i></a>
                        <div class="search-input">
                            <input class="input" type="text" placeholder="Explore Modern...">
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                <span class="mr-1">
                  <span class="user-name text-bold-700">{{admin()->name}}</span>
                </span>
                            <span class="avatar avatar-online">
                  <img src="{{admin()->avatar}}" alt="avatar"><i></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{route('admin.profile')}}"><i class="ft-user"></i> {{__('msg.edit_profile')}}</a>
                            <div class="dropdown-divider"></div>
                            <form method="post" action="{{route('admin.logout')}}">
                                @csrf
                                <button type="submit" class="dropdown-item" href="#"><i class="ft-power"></i> {{__('msg.logout')}}</button>
                            </form>

                        </div>
                    </li>
                    <li class="dropdown dropdown-language nav-item">
                        <a class="dropdown-toggle nav-link"
                           id="dropdown-flag" href="#" data-toggle="dropdown"
                             aria-haspopup="true" aria-expanded="false">
                            @if(\Illuminate\Support\Facades\Session::get('locale') == 'ar')
                                <i class="flag-icon flag-icon-sa"></i>
                            @else
                                <i class="flag-icon flag-icon-us"></i>
                            @endif
                            <span class="selected-language"></span></a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                            <a class="dropdown-item" href="{{url('/change-local/ar')}}"><i class="flag-icon flag-icon-sa"></i> {{__('msg.ar')}}</a>
                            <a class="dropdown-item" href="{{url('/change-local/en')}}"><i class="flag-icon flag-icon-us"></i> {{__('msg.en')}}</a>
                        </div>
                    </li>
                    <?php $notifacations = \App\Models\AdminNotifacation::orderBy('id','DESC')->take(5)->get() ?>
                    <li class="dropdown dropdown-notification nav-item">
                        <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-bell"></i>
                            <span class="badge badge-pill badge-default badge-danger badge-default badge-up badge-glow">{{count($notifacations)}}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <li class="dropdown-menu-header">
                                <h6 class="dropdown-header m-0">
                                    <span class="grey darken-2">{{__('msg.Notifications')}}</span>
                                </h6>
                                <span class="notification-tag badge badge-default badge-danger float-right m-0">{{count($notifacations)}} {{__('msg.New')}}</span>
                            </li>
                            <li class="scrollable-container media-list w-100">
                                @if(count($notifacations) > 0)
                                    @foreach($notifacations as $notify)
                                        <a href="javascript:void(0)">
                                            <div class="media">
                                                <div class="media-left align-self-center"><i class="ft-plus-square icon-bg-circle bg-cyan"></i></div>
                                                <div class="media-body">
                                                    <h6 class="media-heading">{{$notify->title}}</h6>
                                                    <p class="notification-text font-small-3 text-muted">{{$notify->desc}}</p>
                                                    <small>
                                                        <time class="media-meta text-muted">{{date('H:i A',strtotime($notify->created_at))}}</time>
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif


                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
