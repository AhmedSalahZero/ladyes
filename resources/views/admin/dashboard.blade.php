@extends('admin.layouts.app')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- eCommerce statistic -->
                @if(admin()->check_route_permission('dashboard.index') == 1)
{{--                    <div class="row">--}}
{{--                        <div class="col-xl-3 col-lg-6 col-12">--}}
{{--                            <div class="card pull-up">--}}
{{--                                <div class="card-content">--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="media d-flex">--}}
{{--                                            <div class="media-body text-left">--}}
{{--                                                <h3 class="info">{{$count_users}}</h3>--}}
{{--                                                <h6>{{__('msg.count_users')}}</h6>--}}
{{--                                            </div>--}}
{{--                                            <div>--}}
{{--                                                <i class="icon-basket-loaded info font-large-2 float-right"></i>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-xl-3 col-lg-6 col-12">--}}
{{--                            <div class="card pull-up">--}}
{{--                                <div class="card-content">--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="media d-flex">--}}
{{--                                            <div class="media-body text-left">--}}
{{--                                                <h3 class="warning">{{$count_vendors}}</h3>--}}
{{--                                                <h6>{{__('msg.count_vendors')}}</h6>--}}
{{--                                            </div>--}}
{{--                                            <div>--}}
{{--                                                <i class="icon-pie-chart warning font-large-2 float-right"></i>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-xl-3 col-lg-6 col-12">--}}
{{--                            <div class="card pull-up">--}}
{{--                                <div class="card-content">--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="media d-flex">--}}
{{--                                            <div class="media-body text-left">--}}
{{--                                                <h3 class="success">{{$count_open_deals}}</h3>--}}
{{--                                                <h6>{{__('msg.count_open_deals')}}</h6>--}}
{{--                                            </div>--}}
{{--                                            <div>--}}
{{--                                                <i class="icon-user-follow success font-large-2 float-right"></i>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-xl-3 col-lg-6 col-12">--}}
{{--                            <div class="card pull-up">--}}
{{--                                <div class="card-content">--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="media d-flex">--}}
{{--                                            <div class="media-body text-left">--}}
{{--                                                <h3 class="danger">{{$most_ordered ? $most_ordered->deal_name : ''}}</h3>--}}
{{--                                                <p>{{__('msg.most_ordered')}} ({{$most_ordered->total ?? '-'}})</p>--}}
{{--                                            </div>--}}
{{--                                            <div>--}}
{{--                                                <i class="icon-heart danger font-large-2 float-right"></i>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">--}}
{{--                                            <div class="progress-bar bg-gradient-x-danger" role="progressbar"--}}
{{--                                                 style="width: 85%"--}}
{{--                                                 aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    @if(count($recent_orders) > 0)--}}
{{--                        <div class="row">--}}
{{--                            <div id="recent-transactions" class="col-12">--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header">--}}
{{--                                        <h4 class="card-title">{{__('msg.Recent_Transactions')}}</h4>--}}
{{--                                        <a class="heading-elements-toggle"><i--}}
{{--                                                class="la la-ellipsis-v font-medium-3"></i></a>--}}
{{--                                    </div>--}}
{{--                                    <div class="card-content">--}}
{{--                                        <div class="table-responsive">--}}
{{--                                            <table id="recent-orders" class="table table-hover table-xl mb-0">--}}
{{--                                                <thead>--}}
{{--                                                <tr>--}}
{{--                                                    <th class="border-top-0">{{__('msg.Status')}}</th>--}}
{{--                                                    <th class="border-top-0">{{__('msg.Invoice')}}#</th>--}}
{{--                                                    <th class="border-top-0">{{__('msg.Customer_Name')}}</th>--}}
{{--                                                    <th class="border-top-0">{{__('msg.Products')}}</th>--}}
{{--                                                    <th class="border-top-0">{{__('msg.delivery')}}</th>--}}
{{--                                                    <th class="border-top-0">{{__('msg.Amount')}}</th>--}}
{{--                                                </tr>--}}
{{--                                                </thead>--}}
{{--                                                <tbody>--}}
{{--                                                @foreach($recent_orders as $order)--}}
{{--                                                    <tr>--}}
{{--                                                        <td class="text-truncate">{{$order->status_name}}</td>--}}
{{--                                                        <td class="text-truncate"><a--}}
{{--                                                                href="{{route('admin.orders.show',$order-> id)}}">#{{$order->code}}</a>--}}
{{--                                                        </td>--}}
{{--                                                        <td class="text-truncate">{{$order->user->name ?? '-'}}</td>--}}
{{--                                                        <td class="text-truncate p-1">--}}
{{--                                                            <ul class="list-unstyled users-list m-0">--}}
{{--                                                                @if(isset($order->details) && count($order->details) > 0)--}}
{{--                                                                    @foreach($order->details as $detail)--}}
{{--                                                                        <li data-toggle="tooltip"--}}
{{--                                                                            data-popup="tooltip-custom"--}}
{{--                                                                            data-original-title="{{$detail->deal_name}}"--}}
{{--                                                                            class="avatar avatar-sm pull-up">--}}
{{--                                                                            <img--}}
{{--                                                                                class="media-object rounded-circle no-border-top-radius no-border-bottom-radius"--}}
{{--                                                                                src="{{isset($detail->deal->images[0]) ? asset('public'.$detail->deal->images[0]->file_path) : ''}}"--}}
{{--                                                                                alt="{{$detail->deal_name}}">--}}
{{--                                                                        </li>--}}
{{--                                                                    @endforeach--}}
{{--                                                                @endif--}}


{{--                                                            </ul>--}}
{{--                                                        </td>--}}
{{--                                                        <td>--}}
{{--                                                            <button type="button"--}}
{{--                                                                    class="btn btn-sm btn-outline-danger round">{{$order->delivery_name ?? '-'}}</button>--}}
{{--                                                        </td>--}}
{{--                                                        <td class="text-truncate">{{$order->total}} {{__('msg.sar')}}</td>--}}
{{--                                                    </tr>--}}
{{--                                                @endforeach--}}
{{--                                                </tbody>--}}
{{--                                            </table>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <!--/ Recent Transactions -->--}}
{{--                    <!--Recent Orders & Monthly Sales -->--}}
{{--                    <div class="row match-height">--}}
{{--                        <div class="col-xl-12 col-lg-12">--}}
{{--                            <div class="card" style="">--}}
{{--                                <div class="card-content">--}}
{{--                                    <div class="card-body sales-growth-chart">--}}
{{--                                        <div id="monthly-sales" class="height-250"--}}
{{--                                             style="position: relative; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">--}}

{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card-footer">--}}
{{--                                    <div class="chart-title mb-1 text-center">--}}
{{--                                        <h6>{{__('msg.total_monthly_sales')}}</h6>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                    <section id="chartjs-polar-charts">--}}
{{--                        <div class="row">--}}
{{--                            <!-- Polar Chart -->--}}
{{--                            <div class="col-md-12 col-sm-12">--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header">--}}
{{--                                        <h4 class="card-title">Polar Chart</h4>--}}
{{--                                        <a class="heading-elements-toggle"><i--}}
{{--                                                class="la la-ellipsis-v font-medium-3"></i></a>--}}
{{--                                        <div class="heading-elements">--}}
{{--                                            <ul class="list-inline mb-0">--}}
{{--                                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>--}}
{{--                                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>--}}
{{--                                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
{{--                                                <li><a data-action="close"><i class="ft-x"></i></a></li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="card-content collapse show">--}}
{{--                                        <div class="card-body">--}}
{{--                                            <canvas id="polar-chart" height="400"></canvas>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </section>--}}
                @else
                    @include('admin.layouts.alerts.error_perm')
                @endif

            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/assets/vendors/css/charts/chartist.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/assets/vendors/css/charts/chartist-plugin-tooltip.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/vendors/css/weather-icons/climacons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/fonts/meteocons/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/charts/morris.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/assets/css/pages/timeline.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/dashboard-ecommerce.css')}}">
@endsection
@section('scripts')
    <script src="{{asset('assets/vendors/js/charts/chartist.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/charts/chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/charts/chartist-plugin-tooltip.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/charts/raphael-min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/charts/morris.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/timeline/horizontal-timeline.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <script src="{{asset('assets/vendors/js/charts/morris.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts/pages/dashboard-ecommerce.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts/charts/chartjs/polar-radar/polar-skip-points.js')}}"
            type="text/javascript"></script>

{{--    <script>--}}
{{--        var montly_sel = [--}}
{{--                @foreach($montly_sel as $data)--}}
{{--            {--}}
{{--                month: '{{$data['date']}}', sales: {{$data['sum']}}--}}
{{--            }--}}
{{--            @if(! $loop->last)--}}
{{--            ,--}}
{{--            @endif--}}
{{--            @endforeach--}}
{{--        ];--}}
{{--        Morris.Bar({--}}
{{--            element: 'monthly-sales',--}}
{{--            data: montly_sel,--}}
{{--            xkey: 'month',--}}
{{--            ykeys: ['sales'],--}}
{{--            labels: ['Sales'],--}}
{{--            barGap: 4,--}}
{{--            barSizeRatio: 0.3,--}}
{{--            gridTextColor: '#bfbfbf',--}}
{{--            gridLineColor: '#E4E7ED',--}}
{{--            numLines: 5,--}}
{{--            gridtextSize: 14,--}}
{{--            resize: true,--}}
{{--            barColors: ['#FF394F'],--}}
{{--            hideHover: 'auto',--}}
{{--        });--}}
{{--    </script>--}}
{{--    <script>--}}
{{--        $(document).ready(function (){--}}
{{--            var ctx = $("#polar-chart");--}}

{{--            // Chart Options--}}
{{--            var chartOptions = {--}}
{{--                responsive: true,--}}
{{--                maintainAspectRatio: false,--}}
{{--                responsiveAnimationDuration:500,--}}
{{--                legend: {--}}
{{--                    position: 'top',--}}
{{--                },--}}
{{--                title: {--}}
{{--                    display: false,--}}
{{--                    text: 'Chart.js Polar Area Chart'--}}
{{--                },--}}
{{--                scale: {--}}
{{--                    ticks: {--}}
{{--                        beginAtZero: true--}}
{{--                    },--}}
{{--                    reverse: false--}}
{{--                },--}}
{{--                animation: {--}}
{{--                    animateRotate: false--}}
{{--                }--}}
{{--            };--}}

{{--            // Chart Data--}}
{{--            var chartData = {--}}
{{--                labels: [--}}
{{--                    @foreach($montly_profit as $prof)--}}
{{--                        "{{$prof['date']}}"--}}
{{--                    @if(! $loop->last)--}}
{{--                    ,--}}
{{--                    @endif--}}
{{--                        @endforeach--}}

{{--                ],--}}
{{--                datasets: [{--}}
{{--                    data: [--}}
{{--                        @foreach($montly_profit as $prof)--}}
{{--                            {{$prof['sum']}}--}}
{{--                        @if(! $loop->last)--}}
{{--                        ,--}}
{{--                        @endif--}}
{{--                            @endforeach--}}
{{--                    ],--}}
{{--                    backgroundColor: [--}}
{{--                        '#00A5A8', '#626E82', '#FF7D4D','#FF4558', '#28D094',--}}
{{--                        '#74f4f8', '#d8ff33', '#98ffd1','#6dc3ff', '#28D094',--}}
{{--                        '#4fff00', '#ffce4f'--}}
{{--                    ],--}}
{{--                    label: 'My dataset' // for legend--}}
{{--                }],--}}
{{--            };--}}

{{--            var config = {--}}
{{--                type: 'polarArea',--}}

{{--                // Chart Options--}}
{{--                options : chartOptions,--}}

{{--                data : chartData--}}
{{--            };--}}

{{--            // Create the chart--}}
{{--            var polarChart = new Chart(ctx, config);--}}
{{--        })--}}
{{--    </script>--}}
@endsection
