@extends('admin.layouts.app')
@section('content')
@php

@endphp
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- eCommerce statistic -->

            <div class="row">
                <x-dashboard.card :has-progress="true" :progress-percentage="100" :icon="'la-binoculars'" :title="__('No Admins')" value="{{ number_format($adminsCount) }}"> </x-dashboard.card>
                <x-dashboard.card :has-progress="true" :progress-percentage="100" :icon="'la-users'" :title="__('No Users')" value="{{ number_format($clientsCount+$driversCount) }}"> </x-dashboard.card>
                <x-dashboard.card :has-progress="true" :progress-percentage="$clientPercentage" :icon="'la-user'" :title="__('No Clients')" value="{{ number_format($clientsCount) }}"> </x-dashboard.card>
                <x-dashboard.card :has-progress="true" :progress-percentage="$driverPercentage" :icon="'la-user-secret'" :title="__('No Drivers')" value="{{ number_format($driversCount) }}"> </x-dashboard.card>
                <x-dashboard.card :has-progress="true" :progress-percentage="100" :icon="'la-car'" :title="__('No Travels')" value="{{ number_format($travelsCount) }}"> </x-dashboard.card>
                <x-dashboard.card :has-progress="true" :progress-percentage="$completedTravelsPercentage" :icon="'la-car'" :title="__('Completed Travels')" value="{{ number_format($completedTravelsCount) }}"> </x-dashboard.card>
                <x-dashboard.card :has-progress="true" :progress-percentage="$cancelledTravelsPercentage" :icon="'la-car'" :title="__('Cancelled Travels')" value="{{ number_format($cancelledTravelsCount) }}"> </x-dashboard.card>
                <x-dashboard.card :has-progress="true" :progress-percentage="$onTheWayTravelsPercentage" :icon="'la-car'" :title="__('On The Way Travels')" value="{{ number_format($onTheWayTravelsCount) }}"> </x-dashboard.card>
                <x-dashboard.card :has-progress="true" :progress-percentage="$notStartedYetTravelsPercentage" :icon="'la-car'" :title="__('Not Started Yet Travels')" value="{{ number_format($notStartedYetTravelsCount) }}"> </x-dashboard.card>
                {{-- <x-dashboard.card :has-progress="true" :progress-percentage="100" :icon="'la-money'" :title="__('Total Transactions')" value="{{ number_format($totalTransactions) }}" > </x-dashboard.card> --}}
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h1>{{ __('Latest Travels') }}</h1>
                        </div>
                        <div class="card-content collapse show" style="margin-top: -12px">
                            <div class="card-body card-dashboard">
                                <x-tables.basic-table>
                                    <x-slot name="header">
                                        @include('components.common.all-travels-th')
                                    </x-slot>
                                    <x-slot name="body">

                                        @foreach($latestTravels as $model)
                                        @include('components.common.all-travels-tr',['travelType'=>'all'])
                                        @endforeach

                                    </x-slot>
                                </x-tables.basic-table>
                                {{-- @include('admin.helpers.pagination-links') --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h1>{{ __('Latest Transactions') }}</h1>
                        </div>
                        <div class="card-content collapse show" style="margin-top: -12px">
                            <div class="card-body card-dashboard">
                                <x-tables.basic-table>
                                    <x-slot name="header">
                                        @include('admin.transactions.th')
                                    </x-slot>
                                    <x-slot name="body">

                                        @foreach($latestTransactions as $model)
                                        @include('admin.transactions.tr')
                                        @endforeach

                                    </x-slot>
                                </x-tables.basic-table>
                                {{-- @include('admin.helpers.pagination-links') --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>





            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h1>{{ __('Latest Clients') }}</h1>
                        </div>
                        <div class="card-content collapse show" style="margin-top: -12px">
                            <div class="card-body card-dashboard">
                                <x-tables.basic-table>
                                    <x-slot name="header">
                                        @include('admin.clients.th')
                                    </x-slot>
                                    <x-slot name="body">
                                        @foreach($latestClients as $model)
                                        @include('admin.clients.tr')
                                        @endforeach

                                    </x-slot>
                                </x-tables.basic-table>
                                {{-- @include('admin.helpers.pagination-links') --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h1>{{ __('Latest Drivers') }}</h1>
                        </div>
                        <div class="card-content collapse show" style="margin-top: -12px">
                            <div class="card-body card-dashboard">
                                <x-tables.basic-table>
                                    <x-slot name="header">
                                        @include('admin.drivers.th')
                                    </x-slot>
                                    <x-slot name="body">
                                        @foreach($latestDrivers as $model)
                                        @include('admin.drivers.tr')
                                        @endforeach

                                    </x-slot>
                                </x-tables.basic-table>
                                {{-- @include('admin.helpers.pagination-links') --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
						<div class="card-header">
							<h1>{{ __('Number Of Travels Per Months') }} [ {{ now()->format('Y') }} ] </h1>
						</div>
                        <div class="card-body">
							{{-- <input id="no-travels-per-months-value-id" data-value="{{  }}"> --}}
                            <canvas id="no-travels-per-months"></canvas>
                        </div>
                    </div>


                </div>
            </div>
            <!--/ Recent Transactions -->
            <!--Recent Orders & Monthly Sales -->




        </div>
    </div>
</div>
@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('assets/assets/vendors/css/charts/chartist.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/assets/vendors/css/charts/chartist-plugin-tooltip.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/weather-icons/climacons.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/fonts/meteocons/style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/charts/morris.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/assets/css/pages/timeline.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/dashboard-ecommerce.css')}}">
@endsection
@section('scripts')
<script src="{{asset('assets/vendors/js/charts/chartist.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/charts/chart.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/charts/chartist-plugin-tooltip.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/charts/raphael-min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/charts/morris.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/timeline/horizontal-timeline.js')}}" type="text/javascript"></script>
<!-- END PAGE VENDOR JS-->
<script src="{{asset('assets/vendors/js/charts/morris.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/scripts/pages/dashboard-ecommerce.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/scripts/charts/chartjs/polar-radar/polar-skip-points.js')}}" type="text/javascript"></script>


<script>
    const ctx = document.getElementById('no-travels-per-months');
	
	const reportData = @json($numberOfTravelsPerMonthInCurrentYear);
	const labels = ['01', '02', '03', '04', '05', '06' , '07','08','09','10','11','12'] ;
	let reportDataArr = [];
	for(var monthNum of  labels){
		reportDataArr.push(reportData[monthNum]??0)
	}
	console.log('data',reportDataArr);
const data = {
  labels: labels,
  datasets: [{
    label: "{{ __('Number Of Travels Per Months') }}",
    data: reportDataArr,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
    borderWidth: 1
  }]
};

    new Chart(ctx, {
        type: 'bar'
        , data: data,
		options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
    });

</script>


@endsection
