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
                            {{-- <x-form.index-page-header :create-route="$createRoute" :page-title="$pageTitle"> </x-form.index-page-header> --}}
                            {{-- @include('admin.layouts.alerts.success') --}}
                            {{-- @include('admin.layouts.alerts.errors') --}}
                            <div class="card-content collapse show" style="margin-top: -12px">
                                <div class="card-body card-dashboard">
                                    <x-tables.basic-table>
                                        <x-slot name="header">
                                            <th class="th-global-class  text-center">{{ __('Number') }}</th>
                                            <th class="th-global-class  text-center">{{__('Client Name')}}</th>
                                            {{-- <th class="th-global-class  text-center">{{__('Client Phone')}}</th> --}}
                                            <th class="th-global-class  text-center">{{__('Driver Name')}}</th>
                                            {{-- <th class="th-global-class  text-center">{{__('Driver Phone')}}</th> --}}
                                            <th class="th-global-class  text-center">{{__('Total Price')}}</th>
                                            <th class="th-global-class  text-center">{{__('Status')}}</th>
                                            {{-- <th class="th-global-class  text-center">{{__('Name')}}</th> --}}
                                            {{-- <th class="th-global-class  text-center">{{__('Phone')}}</th> --}}
                                            {{-- <th class="th-global-class  text-center">{{__('Message')}}</th> --}}
                                            <th class="th-global-class  text-center">{{__('Date')}}</th>
                                            <th>{{ __('View Details') }}</th>
                                            {{-- @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) ) --}}
                                            {{-- <th class="th-global-class  text-center">{{__('Actions')}}</th> --}}
                                            {{-- @endif --}}
                                        </x-slot>
                                        <x-slot name="body">
                                            @foreach($models as $model)
                                            {{-- {{ dd() }} --}}
                                            <tr data-id="{{ $model->id }}" class="deleteable-row">
                                                <td class="text-center">{{$model->id}}</td>
                                                {{-- <td class="text-center">{{$loop->iteration}}</td> --}}
                                                {{-- <td class="text-center">{{$model->getTypeFormatted($lang)}}</td> --}}
                                                {{-- <td class="text-center">
													{{ $model->getAmountFormatted() }}
                                                </td> --}}
                                                <td class="text-center">{{$model->getClientName()}}</td>
                                                {{-- <td class="text-center">{{$model->getClientPhone()}}</td> --}}
                                                <td class="text-center">{{$model->getDriverName()}}</td>
                                                {{-- <td class="text-center">{{$model->getDriverPhone()}}</td> --}}
                                                <td class="text-center">{{$model->getClientActualTotalPriceFormatted()}}</td>
                                                <td class="text-center">{{$model->getStatusFormatted()}}</td>
                                                {{-- <td class="text-center">{{$model->getNote($lang)}}</td> --}}
                                                <td class="text-center">{{$model->getCreatedAtFormatted()}}</td>

                                                {{-- @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                                <td class="d-flex align-items-center justify-content-sm-center">
                                                    @if($user->can(getPermissionName('delete')))
                                                    <a href="#" data-toggle="modal" data-target="#delete-modal-{{ $model->id }}" class="delete-modal-trigger-js btn btn-danger btn-sm">
                                                <i class="la la-trash"></i></a>
                                                <x-modals.delete :deleteRoute="$deleteRouteName" :model-id="$model->id"></x-modals.delete>
                                                @endif
                                                @if($user->can(getPermissionName('update')))
                                                <a href="{{route($editRouteName,$model->id)}}" class="block-page ml-2 btn btn-primary btn-sm"><i class="la la-pencil"></i></a>
                                                @endif
                                                </td>
                                                @endif --}}
                                                <td>
                                                    @php
                                                    $driver = $model->driver ;
                                                    $client = $model->client ;
                                                    @endphp

                                                    <a title="{{ __('View Details') }}" href="#" data-toggle="modal" data-target="#view-driver-details-popup{{ $model->id }}" class="btn btn-info ml-2  btn-sm">
                                                        <i class="la la-eye"></i></a>


                                                    <div class="modal fade" id="view-driver-details-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Travel No #:travelNumber Details',['travelNumber'=>$model->id]) }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <div class="container emp-profile">
                                                                        <div class="row">

                                                                            <div class="col-md-6">
                                                                                <div class="profile-head">

                                                                                    <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details{{ $model->id }}" role="tab" aria-controls="home" aria-selected="true">{{ __('Driver Details') }}</a>
                                                                                        </li>

                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link" id="card-details-tab" data-toggle="tab" href="#car-details{{ $model->id }}" role="tab" aria-controls="profile" aria-selected="false">{{ __('Car Infos') }}</a>
                                                                                        </li>

                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link " id="client-details-tab" data-toggle="tab" href="#client-details{{ $model->id }}" role="tab" aria-controls="home" aria-selected="true">{{ __('Client Details') }}</a>
                                                                                        </li>
                                                                                        @if($model->isCompleted() )
                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link " id="client-details-tab" data-toggle="tab" href="#prices{{ $model->id }}" role="tab" aria-controls="home" aria-selected="true">{{ __('Payment Details') }}</a>
                                                                                        </li>
                                                                                        @endif

                                                                                        @if($model->isCancelled() )
                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link " id="client-details-tab" data-toggle="tab" href="#fines{{ $model->id }}" role="tab" aria-controls="home" aria-selected="true">{{ __('Fine Details') }}</a>
                                                                                        </li>
                                                                                        @endif



                                                                                    </ul>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">

                                                                            <div class="col-md-10">
                                                                                <div class="tab-content card-details-tab" id="myTabContent">
                                                                                    <div class="tab-pane fade show active" id="details{{ $model->id }}" role="tabpanel" aria-labelledby="details-tab">
                                                                                        <div class="row mb-4">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Driver Image') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <a target="_blank" href="{{ isset($driver) && $driver->getFirstMedia('image') ? $driver->getFirstMedia('image')->getFullUrl() : getDefaultImage() }}">
                                                                                                    <img class="max-w-full profile-max-height" src="{{  isset($driver) && $driver->getFirstMedia('image') ? $driver->getFirstMedia('image')->getFullUrl() : getDefaultImage() }}" alt="{{ isset($driver) ? $driver->getFullName() : __('N/A') }}" title="{{ isset($driver) ?  $driver->getFullName() : __('N/A') }}" />
                                                                                                </a>
                                                                                            </div>
                                                                                        </div>

                                                                                        @foreach( [
                                                                                        __('Full Name') => isset($driver)?$driver->getFullName() : __('N/A'),
                                                                                        __('Id Number') => isset($driver)?$driver->getIdNumber() : __('N/A'),
                                                                                        __('Email') => isset($driver)?$driver->getEmail() : __('N/A'),
                                                                                        __('Phone') => isset($driver)?$driver->getPhone() : __('N/A'),
                                                                                        __('Birth Date')=>isset($driver)?$driver->getBirthDateFormatted() : __('N/A') ] as $title => $value )
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ $title }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $value }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        @endforeach





                                                                                    </div>
                                                                                    <div class="tab-pane fade" id="car-details{{ $model->id }}" role="tabpanel" aria-labelledby="card-details-tab">
                                                                                        @foreach ( [
                                                                                        __('Car Size')=> isset($driver)?$driver->getSizeName($lang) : __('N/A'),
                                                                                        __('Car Make')=> isset($driver)?$driver->getMakeName($lang) : __('N/A'),
                                                                                        __('Car Model') => isset($driver)?$driver->getModelName($lang) : __('N/A'),
                                                                                        __('Manufacturing Year')=>isset($driver)?$driver->getManufacturingYear() : __('N/A'),
                                                                                        __('Plate Letters') => isset($driver)?$driver->getPlateLetters() : __('N/A'),
                                                                                        __('Plate Number') => isset($driver)?$driver->getPlateNumbers() : __('N/A'),
                                                                                        __('Car Color') =>isset($driver)?$driver->getCarColorName() : __('N/A'),
                                                                                        __('Car Max Capacity')=>isset($driver)?$driver->getCarMaxCapacity() : __('N/A'),
                                                                                        __('Car Id Number') => isset($driver)?$driver->getCarIdNumber() : __('N/A'),
                                                                                        __('Has Traffic Tickets ?') => isset($driver)?$driver->getHasTrafficTicketsFormatted() : __('N/A')
                                                                                        ] as $title => $value )
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ $title }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $value }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        @endforeach
                                                                                    </div>






                                                                                    <div class="tab-pane fade " id="client-details{{ $model->id }}" role="tabpanel" aria-labelledby="details-tab">
                                                                                        <div class="row mb-4">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Client Image') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <a target="_blank" href="{{  isset($client) && $client->getFirstMedia('image') ? $client->getFirstMedia('image')->getFullUrl() : getDefaultImage() }}">
                                                                                                    <img class="max-w-full profile-max-height" src="{{  isset($client) && $client->getFirstMedia('image') ? $client->getFirstMedia('image')->getFullUrl() : getDefaultImage() }}" alt="{{ isset($client) ? $client->getFullName() : __('N/A') }}" title="{{ isset($client) ? $client->getFullName() : __('N/A') }}" />
                                                                                                </a>
                                                                                            </div>
                                                                                        </div>

                                                                                        @foreach( [
                                                                                        __('Full Name') => isset($client) ? $client->getFullName() : __('N/A'),
                                                                                        __('Email') => isset($client)?$client->getEmail():__('N/A'),
                                                                                        __('Phone') => isset($client) ? $client->getPhone() :__('N/A'),
                                                                                        __('Birth Date')=>isset($client) ? $client->getBirthDateFormatted() : __('N/A') ] as $title => $value )
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ $title }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $value }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        @endforeach





                                                                                    </div>





                                                                                    @if($model->isCompleted() )

                                                                                    <div class="tab-pane fade" id="prices{{ $model->id }}" role="tabpanel" aria-labelledby="card-details-tab">
                                                                                        @foreach ( [
                                                                                        __('Travel Main Price')=> $model->getPaymentPriceFormatted($lang) ,
                                                                                        __('Coupon Discount') => $model->getPaymentCouponDiscountAmountFormatted() ,
                                                                                        __('Operational Fees') => $model->getPaymentOperationalFeesFormatted() ,
                                                                                        __('Total Price (Without Operation Fees)') => $model->getPaymentTotalPriceWithoutOperationFeesFormatted(),
                                                                                        __('Total Price (With Operation Fees)') => $model->getPaymentTotalPriceWithOperationFeesFormatted(),
                                                                                        __('Payment Method') => $model->getPaymentMethodFormatted(),
                                                                                        __('Payment Status') => $model->getPaymentStatusFormatted(),
                                                                                        ] as $title => $value )
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ $title }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $value }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                    @endif



   																					@if($model->IsCancelled() )

                                                                                    <div class="tab-pane fade" id="fines{{ $model->id }}" role="tabpanel" aria-labelledby="card-details-tab">
                                                                                        @foreach ( [
                                                                                        __('Fine') => $model->getFineAmountFormatted() ,
                                                                                        __('Payment Method') => $model->getFinePaymentMethod()
                                                                                        ] as $title => $value )
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ $title }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $value }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                    @endif



                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        {{-- </form> --}}
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                    {{-- <button type="submit" class="btn btn-primary">{{ __('Send') }}</button> --}}
                                                                </div>

                                                                {{-- </form> --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
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
