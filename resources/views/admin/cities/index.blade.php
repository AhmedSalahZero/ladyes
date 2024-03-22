@extends('admin.layouts.app')
@section('title',$pageTitle)
@section('content')
{{-- {{ dd($pageTitle) }} --}}
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section id="file-export">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <x-form.index-page-header :create-route="$createRoute" :page-title="$pageTitle"> </x-form.index-page-header>
                            {{-- @include('admin.layouts.alerts.success') --}}
                            {{-- @include('admin.layouts.alerts.errors') --}}
                            <div class="card-content collapse show" style="margin-top: -12px">
                                <div class="card-body card-dashboard">
                                    <x-tables.basic-table>
                                        <x-slot name="header">
                                            <th class="th-global-class  text-center">#</th>
                                            <th class="th-global-class  text-center">{{__('Name')}}</th>
                                            <th class="th-global-class  text-center">{{__('Country Name')}}</th>
                                            {{-- <th>{{ __('Main Price') }}</th> --}}
                                            <th>{{ __('Km Price') }}</th>
                                            <th>{{ __('Minute Price') }}</th>
                                            <th>{{ __('Operating Fees') }}</th>
                                            {{-- <th class="th-global-class  text-center">{{__('Rush Hour Price')}}</th> --}}
                                            {{-- <th class="th-global-class  text-center">{{__('Latitude')}}</th> --}}
                                            {{-- <th class="th-global-class  text-center">{{__('Longitude')}}</th> --}}
                                            @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                            <th class="th-global-class  text-center">{{__('Actions')}}</th>
                                            @endif
                                        </x-slot>
                                        <x-slot name="body">
                                            @foreach($models as $model)
                                            <tr data-id="{{ $model->id }}" class="deleteable-row">
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-center">{{$model->getName()}}</td>
                                                <td class="text-center">{{$model->getCountryName($lang)}}</td>
                                                {{-- <td class="text-center">{{$model->getPriceFormatted($lang)}}</td> --}}
                                                <td>{{ $model->getKmPriceFormatted($lang) }}</td>
                                                <td>{{ $model->getMinutePriceFormatted($lang) }}</td>
                                                <td>{{ $model->getOperatingFeesFormatted($lang) }}</td>
                                                @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                                <td class="d-flex align-items-center justify-content-sm-center">


                                                    @if($user->can(getPermissionName('delete')))
                                                    <a href="#" data-toggle="modal" data-target="#delete-modal-{{ $model->id }}" class="delete-modal-trigger-js btn btn-danger btn-sm">
                                                        <i class="la la-trash"></i></a>
                                                    <x-modals.delete :deleteRoute="$deleteRouteName" :model-id="$model->id"></x-modals.delete>
                                                    @endif
                                                    @if($user->can(getPermissionName('update')))
                                                    <a href="{{route($editRouteName,$model->id)}}" class="block-page ml-2 btn btn-primary btn-sm"><i class="la la-pencil"></i></a>
                                                    @endif



                                                    <div class="dropdown ml-2 rush-hourses-details">
                                                        <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Rush Hours') }} </button>
                                                        <div class="dropdown-menu">

                                                            <a data-toggle="modal" data-target="#view-rush-hours-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Rush Hour Details') }} </a>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="view-rush-hours-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                            <div class="modal-content">
                                                                @csrf

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Rush Hours For').' ' . $model->getName($lang) }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="table datatable datatable-js">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="text-center">#</th>
                                                                                <th>{{ __('Start Time') }}</th>
                                                                                <th>{{ __('End Time') }}</th>
                                                                                {{-- <th>{{ __('Main Price') }}</th> --}}
                                                                                <th>{{ __('Km Price') }}</th>
                                                                                <th>{{ __('Minute Price') }}</th>
                                                                                <th>{{ __('Operating Fees') }}</th>
                                                                                <th>{{ __('Rush Hour Percentage') }}</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($model->rushHours as $index=>$rushHour)
                                                                            <tr>
                                                                                <td>{{ $index+1 }}</td>
                                                                                <td>{{ $rushHour->getStartFromFormatted() }}</td>
                                                                                <td>{{ $rushHour->getEndFromFormatted() }}</td>
                                                                                {{-- <td>{{ $rushHour->getPriceFormatted($lang) }}</td> --}}
                                                                                <td>{{ $rushHour->getKmPriceFormatted($lang) }}</td>
                                                                                <td>{{ $rushHour->getMinutePriceFormatted($lang) }}</td>
                                                                                <td>{{ $rushHour->getOperatingFeesFormatted($lang) }}</td>
                                                                                <td>{{ $rushHour->getPercentage() }}</td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                                @endif
                                            </tr>
                                            @endforeach

                                        </x-slot>
                                    </x-tables.basic-table>
                                    @include('admin.helpers.pagination-links')
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<button type="button" class="d-none" id="type-success">Success</button>
@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/tables/datatable/datatables.min.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/extensions/toastr.css')}}"> --}}
{{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css-rtl/plugins/extensions/toastr.css')}}"> --}}
@endsection
@section('scripts')
<script src="{{asset('assets/vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/dataTables.buttons.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/tables/buttons.html5.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/tables/buttons.print.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/scripts/tables/datatables/datatable-advanced.js')}}" type="text/javascript"></script>
{{-- <script src="{{asset('assets/vendors/js/extensions/toastr.min.js')}}" type="text/javascript"></script> --}}
{{-- <script src="{{asset('assets/js/scripts/extensions/toastr.js')}}" type="text/javascript"></script> --}}
@endsection
