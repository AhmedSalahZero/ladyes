@extends('admin.layouts.app')
@section('title','Users')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            @if($user)
                <div class="content-body">
                    <div id="user-profile">
                        <div class="row">
                            <div class="col-12">
                                <div class="card profile-with-cover">
                                    <div class="card-img-top img-fluid bg-cover height-300"
                                         style="background: url({{asset('assets/images/bg_user.avif')}});"></div>
                                    <div class="media profil-cover-details w-100">
                                        <div class="media-left pl-2 pt-2">
                                            @if(isset($user->image))
                                                <a href="#" class="profile-image">
                                                    <img src="{{asset($user->image ? asset(''.$user->image) : 'public/assets/images/plus-96.png')}}"
                                                         width="90px"
                                                         class="rounded-circle img-border height-100"
                                                         alt="Card image">
                                                </a>
                                            @endif
                                        </div>

                                        <div class="media-body pt-3 px-2">
                                            <div class="row">
                                                <div class="col text-right">
                                                    <div class="btn-group d-none d-md-block float-right ml-2"
                                                         role="group" aria-label="Basic example">
                                                        @if(admin()->check_route_permission('admin.users.edit') == 1)
                                                            <a href="{{route('admin.users.edit',$user-> id)}}"
                                                               class="btn btn-success"
                                                               style="border: #28d094 !important;"><i
                                                                    class="la la-cog"></i></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <nav class="navbar navbar-light navbar-profile align-self-end">
                                        <button class="navbar-toggler d-sm-none" type="button" data-toggle="collapse"
                                                aria-expanded="false"
                                                aria-label="Toggle navigation"></button>
                                        <nav class="navbar navbar-expand-lg">
                                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                                <ul class="navbar-nav mr-auto
                                                nav nav-tabs nav-linetriangle no-hover-bg nav-justified">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="active-tab1" data-toggle="tab"
                                                           href="#link1" aria-controls="link1"
                                                           aria-expanded="true">
                                                            <i class="la la-user"></i> {{__('msg.profile')}}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="link-tab2" data-toggle="tab"
                                                           href="#link2" aria-controls="link2"
                                                           aria-expanded="false"><i class="la la-briefcase"></i>
                                                            {{__('msg.orders')}}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="link-tab4" data-toggle="tab"
                                                           href="#link4" aria-controls="link4"
                                                           aria-expanded="false"><i class="la la-star"></i>
                                                            {{__('msg.rates')}}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="link-tab5" data-toggle="tab"
                                                           href="#link5" aria-controls="link5"
                                                           aria-expanded="false"
                                                        ><i class="la la-bell-o"></i>
                                                            {{__('msg.notifications')}}</a>
                                                    </li>
                                                </ul>

                                            </div>
                                        </nav>
                                    </nav>
                                    <div class="tab-content px-1 pt-1">
                                        <div role="tabpanel" class="tab-pane active" id="link1"
                                             aria-labelledby="active-tab1"
                                             aria-expanded="true">
                                            <div class="row">
                                                <div class="col-md-4 col-4">
                                                    <p>{{__('msg.email')}} : {{ $user->email }}</p>
                                                </div>
                                                <div class="col-md-4 col-4">
                                                    <p>{{__('msg.phone')}} : {{ $user->phone }}</p>
                                                </div>
                                                <div class="col-md-4 col-4">
                                                    <p>{{__('msg.active')}}
                                                        : {{ $user->active == 1 ? 'فعال' : 'غير فعال' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="link2" role="tabpanel" aria-labelledby="link-tab2"
                                             aria-expanded="false">
                                                <table class="table table-striped table-bordered dom-jQuery-events">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">{{__('msg.status')}}</th>
                                                        <th class="text-center">{{__('msg.delivery')}}</th>
                                                        <th class="text-center">{{__('msg.delivery_price')}}</th>
                                                        <th class="text-center">{{__('msg.is_payment')}}</th>
                                                        <th class="text-center">{{__('msg.total')}}</th>
                                                        <th class="text-center">{{__('msg.created_at')}}</th>
                                                        <th class="text-center">{{__('msg.action')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
{{--                                                    @if(isset($user->orders) && count($user->orders) > 0)--}}
{{--                                                    @foreach($user->orders as $order)--}}
{{--                                                        <tr class="row_{{$order->id}}">--}}
{{--                                                            <td class="text-center">--}}
{{--                                                                #{{$order->code}}--}}
{{--                                                            </td>--}}
{{--                                                            <td class="text-center">{{$order->status_name}}</td>--}}
{{--                                                            <td class="text-center">{{$order->delivery_name ?? '-'}}</td>--}}
{{--                                                            <td class="text-center">{{(float)$order->delivery_price}} {{__('msg.sar')}}</td>--}}
{{--                                                            <td class="text-center">{{$order->is_payment == 1 ? ' مدفوع':'غير مدفوع'}}</td>--}}
{{--                                                            <td class="text-center">{{(float)$order->total}} {{__('msg.sar')}}</td>--}}
{{--                                                            <td class="text-center">{{date('d/m/Y H:i',strtotime($order->created_at))}}</td>--}}
{{--                                                            <td class="d-flex align-items-center justify-content-sm-center">--}}
{{--                                                                @if(admin()->check_route_permission('admin.orders.show') == 1)--}}
{{--                                                                    <a href="{{route('admin.orders.show',$order->id)}}"--}}
{{--                                                                       class="block-page btn btn-primary btn-sm"><i--}}
{{--                                                                            class="la la-eye"></i></a>--}}
{{--                                                                @endif--}}
{{--                                                            </td>--}}
{{--                                                        </tr>--}}
{{--                                                    @endforeach--}}
{{--                                                    @endif--}}
                                                    </tbody>
                                                </table>

                                        </div>
                                        <div class="tab-pane" id="link4" role="tabpanel" aria-labelledby="link-tab4"
                                             aria-expanded="false">
                                            <table class="table table-striped table-bordered dom-jQuery-events">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">{{__('msg.driver')}}</th>
                                                    <th class="text-center">{{__('msg.rate')}}</th>
                                                    <th class="text-center">{{__('msg.text')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($user->rates) && count($user->rates) > 0)
                                                    @foreach($user->rates as $rate)
                                                        <tr>
                                                            <td class="text-center">{{$rate->id}}</td>
                                                            <td class="text-center">{{$rate->driver->name ?? '-'}}</td>
                                                            <td class="d-flex justify-content-center">
                                                                @for($i = 1; $i<=5 ; $i++)
                                                                    <li class="la la-star" style="{{$rate->rate >= $i ? 'color: #fdbe00e0;' : ''}}"></li>
                                                                @endfor
                                                            </td>
                                                            <td class="text-center">{{$rate->text}}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="link5" role="tabpanel" aria-labelledby="link-tab5"
                                             aria-expanded="false">
                                            <table class="table table-striped table-bordered dom-jQuery-events">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">{{__('msg.title')}}</th>
                                                    <th class="text-center">{{__('msg.text')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($user->notifications) && count($user->notifications) > 0)
                                                    @foreach($user->notifications as $notify)
                                                        <tr class="row_{{$notify->id}}">
                                                            <td class="text-center">
                                                                #{{$notify->id}}
                                                            </td>
                                                            <td class="text-center">{{$notify->title}}</td>
                                                            <td class="text-center">{{$notify->text ?? '-'}}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                        <p class="alert alert-danger">{{__('msg.not_found')}}</p>
                    @endif
                </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/users.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/vendors/css/tables/datatable/datatables.min.css')}}">
    <style>
        #DataTables_Table_0_wrapper {
            margin-top: 10px;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('assets/vendors/js/tables/datatable/datatables.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts/tables/datatables/datatable-advanced.js')}}"
            type="text/javascript"></script>
@endsection
