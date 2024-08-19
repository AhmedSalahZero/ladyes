@extends('admin.layouts.app')
@section('title',$pageTitle)
@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section id="file-export">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            {{-- <x-form.index-page-header :create-route="$createRoute" :page-title="$pageTitle"> </x-form.index-page-header> --}}
                            <div class="card-content collapse show" style="margin-top: -12px">
                                <div class="card-body card-dashboard">
                                    <x-tables.basic-table>
                                        <x-slot name="header">
                                            <th class="th-global-class  text-center">#</th>
                                            <th class="th-global-class  text-center">{{__('User')}}</th>
                                            <th class="th-global-class  text-center">{{__('User Type')}}</th>
                                            <th class="th-global-class  text-center">{{__('Payment Type')}}</th>
                                            <th class="th-global-class  text-center">{{__('Total Price')}}</th>
                                            <th class="th-global-class  text-center">{{__('Status')}}</th>
                                            <td class="th-global-class  text-center">{{ __('Operation Date') }}</td>
                                            <th class="th-global-class  text-center">{{__('Details')}}</th>
                                            {{-- @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                            @endif --}}
                                        </x-slot>
                                        <x-slot name="body">
                                            @foreach($models as $model)
                                            <tr data-id="{{ $model->id }}" class="deleteable-row">
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-center">{{$model->getUserName($lang)}}</td>
                                                <td class="text-center">{{$model->getModelTypeFormatted($lang)}}</td>
                                                <td class="text-center">{{$model->getTypeFormatted()}}</td>

                                                <td class="text-center">{{$model->getTotalPriceFormatted()}}</td>
                                                <td class="text-center">{{$model->getStatusFormatted()}}</td>
                                                <td class="text-center">{{ formatForView($model->created_at) }}</td>
                                                <td class="text-center">
                                                    <a title="{{ __('View Details') }}" href="#" data-toggle="modal" data-target="#view-payment-details-popup{{ $model->id }}" class="btn btn-info ml-2  btn-sm">
                                                        <i class="la la-eye"></i></a>

                                                    <div class="modal fade" id="view-payment-details-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                            <div class="modal-content">
                                                                @csrf

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Payment Details') }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="table datatable datatable-js">
                                                                        <thead>
                                                                            <tr>
                                                                                {{-- <th class="text-center">#</th> --}}
                                                                                <th>{{ __('Price') }}</th>
                                                                                <th>{{ __('Operational Fees') }}</th>
                                                                                <th>{{ __('Discount') }}</th>
                                                                                <th>{{ __('Cash Fees') }}</th>
                                                                                <th>{{ __('Tax Amount') }}</th>
                                                                                <th>{{ __('Total Fine') }}</th>
                                                                                {{-- <th>{{ __('Date') }}</th> --}}
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="text-center">{{$model->getPriceFormatted()}}</td>
                                                                                <td class="text-center">{{$model->getOperationalFeesFormatted()}}</td>
                                                                                <td class="text-center">{{$model->getCouponDiscountAmountFormatted()}}</td>
                                                                                <td class="text-center">{{$model->getCashFeesFormatted()}}</td>
                                                                                <td class="text-center">{{$model->getTaxAmountFormatted()}}</td>
                                                                                <td class="text-center">{{$model->getTotalFineAmountFormatted()}}</td>

                                                                            </tr>
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
