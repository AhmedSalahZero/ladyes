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
                                            <th class="th-global-class  text-center">#</th>
                                            <th class="th-global-class  text-center">{{__('User Type')}}</th>
                                            <th class="th-global-class  text-center">{{__('Name')}}</th>
                                            <th class="th-global-class  text-center">{{__('Subject')}}</th>
                                            <th class="th-global-class  text-center">{{__('Message')}}</th>
                                            <th class="th-global-class  text-center">{{__('')}}</th>
                                            {{-- <th class="th-global-class  text-center">{{__('Image')}}</th> --}}
                                            {{-- <th class="th-global-class  text-center">{{__('Price')}}</th> --}}
                                            @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                            <th class="th-global-class  text-center">{{__('Actions')}}</th>
                                            @endif
                                        </x-slot>
                                        <x-slot name="body">
                                            @foreach($models as $model)
                                            <tr data-id="{{ $model->id }}" class="deleteable-row">
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                  <td class="text-center">{{$model->getUserName($lang)}}</td>
                                                <td class="text-center">{{$model->getModelTypeFormatted()}}</td>
                                                <td class="text-center">{{$model->getSubject($lang)}}</td>
                                                <td class="text-center">{{$model->getMessage($lang)}}</td>
                                                {{-- <td class="text-center">{{$model->getPriceFormatted($lang)}}</td> --}}

                                                @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                                <td class="d-flex align-items-center justify-content-sm-center">
                                                    {{-- @if($user->can(getPermissionName('delete')))
                                                    <a href="#" data-toggle="modal" data-target="#delete-modal-{{ $model->id }}" class="delete-modal-trigger-js btn btn-danger btn-sm">
                                                    <i class="la la-trash"></i></a>
                                                    <x-modals.delete :deleteRoute="$deleteRouteName" :model-id="$model->id"></x-modals.delete>
                                                    @endif --}}
                                                    @if($user->can(getPermissionName('update')))
                                                    <a href="{{route($editRouteName,$model->id)}}" class="block-page ml-2 btn btn-primary btn-sm"><i class="la la-pencil"></i></a>
                                                    <a title="{{ __('Edit Prices') }}" href="#" class="block-page ml-2 btn btn-primary btn-sm " data-toggle="modal" data-target="#edit-prices-per-country-{{ $model->id }}"><i class="la la-money"></i></a>
                                                    <div class="modal fade" id="edit-prices-per-country-{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('car-sizes.update.prices',['carSize'=>$model->id]) }}" method="post">
                                                                    @csrf
                                                                    @method('patch')
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Car Size Price :name',['name'=>$model->getName($lang)]) }}</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="table table-striped table-bordered">
                                                                            <tr>
                                                                                <th>
                                                                                    {{ __('Country Name') }}
                                                                                </th>
                                                                                <th>
                                                                                    {{ __('Price') }}
                                                                                </th>
                                                                            </tr>
                                                                            @foreach($model->countryPrices as $countryWithPriceAsPivot)
                                                                            <tr>

                                                                                <td>
                                                                                    <input class="form-control" disabled value="{{ $countryWithPriceAsPivot->getName($lang) }}">
                                                                                </td>
                                                                                <td >
                                                                                    <div class="d-flex align-items-center" style="gap:0.4rem">
                                                                                        <input required name="prices[{{ $countryWithPriceAsPivot->id }}]" type="numeric" step="0" value="{{ $countryWithPriceAsPivot->pivot->price }}" class="form-control"> <span>
                                                                                            {{ $countryWithPriceAsPivot->getCurrencyFormatted() }}
                                                                                        </span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </table>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                        <button type="submit" class="btn btn-primary js-save-by-ajax">{{ __('Save') }}</button>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
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
