@extends('admin.layouts.app')
@section('title','Orders')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">{{__('msg.site_name')}}</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{route('dashboard.index')}}">{{__('msg.dashboard')}} </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{route('admin.orders.index')}}">{{__('msg.orders')}}</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="card">
                    @if(admin()->check_route_permission('admin.orders.show') == 1)
                        <div id="invoice-template" class="card-body">
                            <!-- Invoice Company Details -->
                            <div id="invoice-company-details" class="row">
                                <div class="col-md-6 col-sm-12 text-center text-md-left">
                                    <div class="media">
                                        <img src="{{asset('public'.$setting->logo)}}" style="width: 120px;"
                                             alt="company logo" class=""
                                        />
                                        <div class="media-body">
                                            <ul class="ml-2 px-0 list-unstyled">
                                                <li class="text-bold-800">{{__('msg.site_name')}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 text-center text-md-right">
                                    <h2>{{__('msg.INVOICE')}}</h2>
                                    <p><span
                                            class="text-muted">{{__('msg.code')}} :</span># {{$order->code}}</p>
                                    <p><span
                                            class="text-muted">{{__('msg.Invoice_Date')}} :</span> {{date('d/m/Y H:i',strtotime($order->created_at))}}
                                    </p>
                                    <p><span
                                            class="text-muted">{{__('msg.is_payment')}} :</span> {{$order->is_payment == 1 ? 'مدفوع':'غير مدفوع'}}
                                    </p>
                                    <p><span
                                            class="text-muted">{{__('msg.payment_method')}} :</span> {{$order->payment_method_name}}
                                    </p>
                                    <p><span class="text-muted">{{__('msg.status')}} :</span> {{$order->status_name}}
                                    </p>
                                </div>
                            </div>
                            <!--/ Invoice Company Details -->
                            <!-- Invoice Customer Details -->
                            <div id="invoice-customer-details" class="row pt-2">
                                <div class="col-md-4 text-center text-md-left">
                                    <p class="text-muted">{{__('msg.user')}}</p>
                                    <hr>
                                </div>
                                <div class="col-md-4 text-center text-md-left">
                                    <p class="text-muted">{{__('msg.driver')}}</p>
                                    <hr>
                                </div>
                                <div class="col-md-4 text-center text-md-left">
                                    <p class="text-muted">{{__('msg.car')}}</p>
                                    <hr>
                                </div>
                                <div class="col-md-4 col-md-4 text-center text-md-left">
                                    <ul class="px-0 list-unstyled">
                                        <li><img
                                                src="{{isset($order->user) && $order->user->image ? asset('public'.$order->user->image) : ''}}"
                                                width="70px"></li>
                                        <li class="text-bold-800">{{__('msg.name')}}
                                            :{{$order->user->first_name ?? '-'}}  {{$order->user->last_name ?? '-'}}</li>
                                        <li>{{__('msg.email')}} : {{$order->user->email ?? '-'}}</li>
                                        <li>{{__('msg.phone')}} : {{$order->user->phone ?? '-'}}</li>
                                    </ul>
                                </div>


                                <div class="col-md-4 col-md-4 text-center text-md-left">
                                    <ul class="px-0 list-unstyled">
                                        <li><img
                                                src="{{isset($order->driver->file('driver_image')->file_path) && $order->driver->file('driver_image')->file_path ? asset('public'.$order->driver->file('driver_image')->file_path) : ''}}"
                                                width="70px"></li>
                                        <li class="text-bold-800">{{__('msg.name')}} : {{$order->driver->first_name ?? '-'}}  {{$order->driver->last_name ?? '-'}}</li>
                                        <li>{{__('msg.email')}} : {{$order->driver->email ?? '-'}}</li>
                                        <li>{{__('msg.phone')}} : {{$order->driver->phone ?? '-'}}</li>
                                    </ul>
                                </div>
                                <div class="col-md-4 col-md-4 text-center text-md-left">
                                    <ul class="px-0 list-unstyled">
                                        <li><img
                                                src="{{isset($order->driver->file('car_image')->file_path) ? asset('public'.$order->driver->file('car_image')->file_path) : ''}}"
                                                width="70px"></li>
                                        <li class="text-bold-800">{{__('msg.car_number')}}
                                            : {{$order->driver->car_number ?? '-'}} </li>
                                        <li>{{__('msg.car_color')}} :{{$order->driver->car_color ?? '-'}}</li>
                                        <li>{{__('msg.prod_year')}} :{{$order->driver->prod_year ?? '-'}}</li>
                                    </ul>
                                </div>
                            </div>

                            <div id="invoice-items-details" class="pt-2">
                                <div class="row">
                                    <div class="table-responsive col-sm-12 mt-2">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th class="text-center">{{__('msg.from_address')}}</th>
                                                <th class="text-center">{{__('msg.to_address')}}</th>
                                                <th class="text-center">{{__('msg.travel_type')}}</th>
                                                <th class="text-center">{{__('msg.price')}}</th>
                                                <th class="text-center">{{__('msg.meters')}}</th>
                                                <th class="text-center">{{__('msg.penalty_price')}}</th>
                                                <th class="text-center">{{__('msg.total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr>
                                                <td class="text-center">{{$order->from_location_info ?? '-'}}</td>
                                                <td class="text-center">{{$order->to_location_info ?? '-'}}</td>
                                                <td class="text-center">{{$order->travel_type_name}}</td>
                                                <td class="text-center">{{round($order->price,2)}}</td>
                                                <td class="text-center">{{round($order->meters,2)}}</td>
                                                <td class="text-center">{{round($order->penalty_price,2)}}</td>
                                                <td class="text-center">{{round(($order->sub_total + $order->penalty_price),2)}}</td>

                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 col-sm-12 text-center text-md-left">

                                    </div>
                                    <div class="col-md-5 col-sm-12">
                                        <p class="lead">{{__('msg.summery')}}</p>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td>{{__('msg.sub_total')}}</td>
                                                    <td class="text-right"><span
                                                            id="order_sub_total">{{round($order->sub_total,2)}}</span>
                                                        <span>{{__('msg.sar')}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('msg.tax')}}</td>
                                                    <td class="text-right"><span
                                                            id="total_tax">{{round($order->vat_price,2)}}</span>
                                                        <span>{{__('msg.sar')}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('msg.penalty_price')}}</td>
                                                    <td class="text-right"><span
                                                            id="total_tax">{{round($order->penalty_price,2)}}</span>
                                                        <span>{{__('msg.sar')}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('msg.discount_price')}}</td>
                                                    <td class="text-right"><span
                                                            id="total_tax">{{round($order->discount_price,2)}}</span>
                                                        <span>{{__('msg.sar')}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-bold-800">{{__('msg.total')}}</td>
                                                    <td class="text-bold-800 text-right"><span
                                                            id="total"> {{round($order->total,2)}}</span>
                                                        <span>{{__('msg.sar')}}</span></td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <p>{{__('msg.traking_trip')}}</p>
                                <div id="map" style="height: 400px;"></div>
                            </div>

                        </div>
                    @else
                        @include('admin.layouts.alerts.error_perm')
                    @endif
                </section>
            </div>
        </div>
    </div>

@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/invoice.css')}}">
@endsection
@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-goXOqVqgntbrPBGMszydKihARSWoWiQ&callback=initMap"
            async defer></script>
    <script>
        function initMap() {
            const fromLat = {{$order->from_lat ?? ''}}; // Replace with your from_lat
            const fromLng = {{$order->from_lng ?? ''}}; // Replace with your from_lng
            const toLat = {{$order->to_lat ?? ''}}; // Replace with your to_lat
            const toLng = {{$order->to_lng ?? ''}}; // Replace with your to_lng

            const map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: (fromLat + toLat) / 2, lng: (fromLng + toLng) / 2 },
                zoom: 8
            });

            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: true // Suppress default start and end markers
            });

            const fromLatLng = new google.maps.LatLng(fromLat, fromLng);
            const toLatLng = new google.maps.LatLng(toLat, toLng);

            const request = {
                origin: fromLatLng,
                destination: toLatLng,
                travelMode: google.maps.TravelMode.DRIVING // You can change the travel mode as needed
            };

            directionsService.route(request, function(response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(response);
                } else {
                    console.error('Directions request failed due to ' + status);
                }
            });
        }
    </script>
@endsection
