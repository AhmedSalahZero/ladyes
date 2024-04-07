@extends('admin.layouts.app')
@push('css')
<link rel="stylesheet" href="{{ asset('custom/css/model-details.css') }}">

@endpush
@section('title','Admins')

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
                            {{-- @include('admin.layouts.alerts.fail') --}}
                            <div class="card-content collapse show" style="margin-top: -12px">
                                <div class="card-body card-dashboard">
                                    <x-tables.basic-table>
                                        <x-slot name="header">
                                            <th class="th-global-class  text-center">#</th>
                                            <th class="th-global-class  text-center">{{__('Name')}}</th>
                                            <th class="th-global-class  text-center">{{__('Image')}}</th>
                                            <th class="th-global-class  text-center">{{__('Phone')}}</th>
                                            <th class="th-global-class  text-center">{{__('Email')}}</th>
                                            <th class="th-global-class  text-center">{{__('Is Verified')}}</th>
                                            @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                            <th class="th-global-class  text-center">{{__('Actions')}}</th>
                                            @endif
                                        </x-slot>
                                        <x-slot name="body">
                                            @foreach($models as $model)
                                            <tr data-id="{{ $model->id }}" class="deleteable-row">
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-center">{{$model->getFullName()}}</td>
                                                <td class="text-center">
                                                    <img class="default-img-in-td" src="{{ $model->getFirstMedia('image') ? $model->getFirstMedia('image')->getFullUrl() : getDefaultImage() }}">
                                                </td>
                                                <td class="text-center">{{$model->getPhone()}}</td>
                                                <td class="text-center">{{$model->getEmail()}}</td>
                                                <td class="text-center">
                                                    @if($user->can(getPermissionName('update')))
                                                    <div class="form-group pb-1">
                                                        <x-form.checkbox-element data-toggle-route="{{ $toggleIsVerifiedRoute }}" data-id="{{ $model->id }}" class="switch-trigger-js" :is-required="false" :name="'is_verified'" :is-checked="$model->getIsVerified()"> </x-form.checkbox-element>
                                                    </div>
                                                    @else
                                                    {{$model->isBanned() ? __('Banned') : __('Not Banned')}}
                                                    @endif
                                                </td>
                                                @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                                <td class="">
                                                    @if($user->can(getPermissionName('delete')))
                                                    <a href="#" data-toggle="modal" data-target="#delete-modal-{{ $model->id }}" class="delete-modal-trigger-js btn btn-danger btn-sm">
                                                        <i class="la la-trash"></i></a>
                                                    <x-modals.delete :deleteRoute="$deleteRouteName" :model-id="$model->id"></x-modals.delete>
                                                    @endif
                                                    <a title="{{ __('View Details') }}" href="#" data-toggle="modal" data-target="#view-driver-details-popup{{ $model->id }}" class="btn btn-info ml-2  btn-sm">
                                                        <i class="la la-eye"></i></a>

                                                    <div class="modal fade" id="view-driver-details-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Driver Details :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <div class="container emp-profile">
                                                                        {{-- <form method="post"> --}}
                                                                        <div class="row">
                                                                            <div class="col-md-2">
                                                                                <div class="profile-img">
                                                                                    <a target="_blank" href="{{ isset($model) && $model->getFirstMedia('image') ? $model->getFirstMedia('image')->getFullUrl() : getDefaultImage() }}">
                                                                                        <img class="max-w-full profile-max-height" src="{{ isset($model) && $model->getFirstMedia('image') ? $model->getFirstMedia('image')->getFullUrl() : getDefaultImage() }}" alt="{{ $model->getFullName() }}" title="{{ $model->getFullName() }}" />
                                                                                    </a>
                                                                                    {{-- <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS52y5aInsxSm31CvHOFHWujqUx_wWTS9iM6s7BAm21oEN_RiGoog" alt="" /> --}}
                                                                                    {{-- <div class="file btn btn-lg btn-primary">
                                                                                                        Change Photo
                                                                                                        <input type="file" name="file" />
                                                                                                    </div> --}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="profile-head">
                                                                                    <h3 class="font-weight-bold">
                                                                                        {{ $model->getFullName() }}
                                                                                    </h3>
                                                                                    <h4 class="font-weight-bold">
                                                                                        {{ $model->getPhone() }}
                                                                                    </h4>
                                                                                    <h4 class="font-weight-bold">
                                                                                        {{ $model->getEmail() }}
                                                                                    </h4>

                                                                                    <h4 class="proile-rating font-weight-bold">{{ __('Rating') }} : <span class="font-weight-bold">{{ $model->getAvgRateFormatted(true) }}/{{ $model->getMaxRatingPoint() }}</span></h4>
                                                                                    <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details{{ $model->id }}" role="tab" aria-controls="home" aria-selected="true">{{ __('Driver Details') }}</a>
                                                                                        </li>
                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link " id="driver-images-tab" data-toggle="tab" href="#driver-images{{ $model->id }}" role="tab" aria-controls="home" aria-selected="true">{{ __('Driver Images') }}</a>
                                                                                        </li>
                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link" id="card-details-tab" data-toggle="tab" href="#car-details{{ $model->id }}" role="tab" aria-controls="profile" aria-selected="false">{{ __('Car Infos') }}</a>
                                                                                        </li>

                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link " id="car-images-tab" data-toggle="tab" href="#car-images{{ $model->id }}" role="tab" aria-controls="home" aria-selected="true">{{ __('Car Images') }}</a>
                                                                                        </li>

                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                            @if($user->can(getPermissionName('update')))
                                                                            <div class="col-md-2">
                                                                                <a href="{{route($editRouteName,$model->id)}}" class="btn btn-primary ">{{ __('Edit') }}
                                                                                    <i class="la la-pencil"></i>
                                                                                </a>



                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <a data-toggle="modal" data-target="#ban-account-popup{{ $model->id }}" href="#" class="btn @if($model->isBanned()) btn-success @else btn-danger @endif  ban-account-js">
                                                                                    @if($model->isBanned())
                                                                                    {{ __('Unban') }}
                                                                                    @else
                                                                                    {{ __('Ban') }}
                                                                                    @endif
                                                                                    <i class="la la-ban"></i>
                                                                                </a>




                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-2">
                                                                                {{-- <div class="profile-work">
                                                                                                    <p>WORK LINK</p>
                                                                                                    <a href="">Website Link</a><br />
                                                                                                    <a href="">Bootsnipp Profile</a><br />
                                                                                                    <a href="">Bootply Profile</a>
                                                                                                    <p>SKILLS</p>
                                                                                                    <a href="">Web Designer</a><br />
                                                                                                    <a href="">Web Developer</a><br />
                                                                                                    <a href="">WordPress</a><br />
                                                                                                    <a href="">WooCommerce</a><br />
                                                                                                    <a href="">PHP, .Net</a><br />
                                                                                                </div> --}}
                                                                            </div>
                                                                            <div class="col-md-10">
                                                                                <div class="tab-content card-details-tab" id="myTabContent">
                                                                                    <div class="tab-pane fade show active" id="details{{ $model->id }}" role="tabpanel" aria-labelledby="details-tab">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Id Number') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $model->getIdNumber() }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Deduction Percentage') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $model->getDeductionPercentage() . ' %' }}</p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Country Name') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $model->getCountryName($lang) }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('City Name') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $model->getCityName($lang) }}</p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Is Verified') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold js-is-verified" data-model-id="{{ $model->id }}">{{ $model->getIsVerifiedFormatted() }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Banned') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold ">{{ $model->isBanned() ? __('Yes') : __('No') }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Is Listing To Orders Now') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold"> {{ $model->getIsListingToOrdersFormatted() }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">

                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Verification Code') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $model->getVerificationCode() }}</p>
                                                                                            </div>

                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Invitation Code') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $model->getInvitationCode() }}</p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <label class="font-weight-bold">{{ __('Birth Date') }}</label>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <p class="font-weight-bold">{{ $model->getBirthDateFormatted() }}</p>
                                                                                            </div>
                                                                                        </div>








                                                                                    </div>
                                                                                    <div class="tab-pane fade" id="driver-images{{ $model->id }}" role="tabpanel" aria-labelledby="driver-images-tab">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <x-form.image-uploader :click-to-enlarge="true" :editable="false" :required="false" :name="'image'" :id="'image--id'" :label="__('Image')" :image="isset($model) && $model->getFirstMedia('image') ? $model->getFirstMedia('image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <x-form.image-uploader :click-to-enlarge="true" :editable="false" :required="false" :name="'id_number_image'" :id="'id_number_image'" :label="__('Id Number')" :image="isset($model) && $model->getFirstMedia('id_number_image') ? $model->getFirstMedia('id_number_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                                                            </div>

                                                                                            <div class="col-md-6">
                                                                                                <x-form.image-uploader :click-to-enlarge="true" :editable="false" :required="false" :name="'insurance_image'" :id="'insurance_image'" :label="__('Insurance Image')" :image="isset($model) && $model->getFirstMedia('insurance_image') ? $model->getFirstMedia('insurance_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                                                            </div>

                                                                                            <div class="col-md-6">
                                                                                                <x-form.image-uploader :click-to-enlarge="true" :editable="false" :required="false" :name="'driver_license_image'" :id="'driver_license_image'" :label="__('Driver License Image')" :image="isset($model) && $model->getFirstMedia('driver_license_image') ? $model->getFirstMedia('driver_license_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="tab-pane fade" id="car-details{{ $model->id }}" role="tabpanel" aria-labelledby="card-details-tab">
                                                                                        @foreach ( [
                                                                                        __('Car Size')=> $model->getSizeName($lang),
                                                                                        __('Car Make')=> $model->getMakeName($lang),
                                                                                        __('Car Model') => $model->getModelName($lang),
                                                                                        __('Manufacturing Year')=>$model->getManufacturingYear(),
                                                                                        __('Plate Letters') => $model->getPlateLetters(),
                                                                                        __('Plate Number') => $model->getPlateNumbers(),
                                                                                        __('Car Color') =>$model->getCarColorName(),
                                                                                        __('Car Max Capacity')=>$model->getCarMaxCapacity(),
                                                                                        __('Car Id Number') => $model->getCarIdNumber(),
                                                                                        __('Has Traffic Tickets') => $model->getHasTrafficTicketsFormatted()
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


                                                                                    <div class="tab-pane fade" id="car-images{{ $model->id }}" role="tabpanel" aria-labelledby="car-images-tab">
                                                                                        <div class="row">

                                                                                            <div class="col-md-6">
                                                                                                <x-form.image-uploader :click-to-enlarge="true" :editable="false" :required="false" :name="'front_image'" :id="'front_image'" :label="__('Front Car Image')" :image="isset($model) && $model->getFirstMedia('front_image') ? $model->getFirstMedia('front_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                                                            </div>

                                                                                            <div class="col-md-6">
                                                                                                <x-form.image-uploader :click-to-enlarge="true" :editable="false" :required="false" :name="'back_image'" :id="'back_image'" :label="__('Back Car Image')" :image="isset($model) && $model->getFirstMedia('back_image') ? $model->getFirstMedia('back_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        {{-- </form> --}}
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                    <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                </div>

                                                                {{-- </form> --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade inner-modal" id="ban-account-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('driver.toggle.is.banned') }}" method="post">
                                                                    @method('put')
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $model->id }}">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            @if($model->isBanned())
                                                                            {{ __('Do You Want To Unban :name Account',['name'=>$model->getFullName($lang)]) }}
                                                                            @else
                                                                            {{ __('Do You Want To Ban :name Account',['name'=>$model->getFullName($lang)]) }}
                                                                            @endif
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea name="comment" class="form-control" rows="8" type="text" required placeholder="{{ __('Reason In Details') }}"></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss-modal="inner-modal">{{ __('Close') }}</button>
                                                                        @if($model->isBanned())
                                                                        <button type="submit" class="btn btn-success">{{ __('Unban') }}</button>
                                                                        @else
                                                                        <button type="submit" class="btn btn-danger">{{ __('Ban') }}</button>
                                                                        @endif
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if($user->can(getPermissionName('update')))
                                                    <a href="{{route($editRouteName,$model->id)}}" class="block-page ml-2 btn btn-primary btn-sm"><i class="la la-pencil"></i></a>
                                                    <div class="dropdown-grid-css">
                                                        <div class="dropdown  send-message">
                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Send Message') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#send-whatsapp-message-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Whatsapp') }} </a>
                                                                <a data-toggle="modal" data-target="#send-sms-message-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Sms') }} </a>
                                                                <a data-toggle="modal" data-target="#send-email-message-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Email') }} </a>
                                                            </div>
                                                            <div class="modal fade" id="send-whatsapp-message-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.whatsapp.message') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">
                                                                            <input type="hidden" name="phone" value="{{ $model->getPhone() }}">
                                                                            <input type="hidden" name="country_code" value="{{ $model->getCountryIso2() }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Send Whatsapp Message To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <textarea name="message" class="form-control" rows="8" type="text" required placeholder="{{ __('Message Text') }}"></textarea>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade" id="send-sms-message-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.sms.message') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">
                                                                            <input type="hidden" name="phone" value="{{ $model->getPhone() }}">
                                                                            <input type="hidden" name="country_code" value="{{ $model->getCountryIso2() }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Send Sms Message To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <textarea name="message" class="form-control" rows="8" type="text" required placeholder="{{ __('Message Text') }}"></textarea>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="modal fade" id="send-email-message-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.email.message') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="email" value="{{ $model->getEmail() }}">
                                                                            <input type="hidden" name="name" value="{{ $model->getFullName() }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Send Email Message To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <input class="form-control" required type="text" name="subject" placeholder="{{ __('Message Subject') }}">
                                                                                </div>
                                                                                <div class="from-group">
                                                                                    <textarea name="message" class="form-control" rows="8" type="text" required placeholder="{{ __('Message Text') }}"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="dropdown ml-2 inventation-codes">

                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Invention Codes') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#view-sent-invitations-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Sent') }} </a>
                                                                <a data-toggle="modal" data-target="#view-recevied-invitations-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Received') }} </a>
                                                                <a data-toggle="modal" data-target="#add-invintation-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Add Invitation') }} </a>
                                                            </div>
                                                            <div class="modal fade" id="view-sent-invitations-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">{{ __('Sent Invitation Codes For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Receiver Name') }}</th>
                                                                                        <th>{{ __('Code') }}</th>
                                                                                        <th>{{ __('Date') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->sentInvitationCodes as $index=>$sentInvitationCode)
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ \App\Models\Driver::getNameById($sentInvitationCode->pivot->receiver_id) }}</td>
                                                                                        <td>{{ $sentInvitationCode->pivot->code }}</td>
                                                                                        <td>{{ formatForView($sentInvitationCode->pivot->created_at) }}</td>
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

                                                            <div class="modal fade" id="view-recevied-invitations-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">{{ __('Received Invitation Codes For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Sender Name') }}</th>
                                                                                        <th>{{ __('Code') }}</th>
                                                                                        <th>{{ __('Date') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->receivedInvitationCodes as $index=>$receivedInvitationCode)
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ \App\Models\Driver::getNameById($receivedInvitationCode->pivot->driver_id) }}</td>
                                                                                        <td>{{ $receivedInvitationCode->pivot->code }}</td>
                                                                                        <td>{{ formatForView($receivedInvitationCode->pivot->created_at) }}</td>
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


                                                            <div class="modal fade" id="add-invintation-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('add.invitation.code.to.driver') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="receiver_id" value="{{ $model->id }}">
                                                                            {{-- <input type="hidden" name="name" value="{{ $model->getFullName() }}"> --}}
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Attach Invitation Code To :name' ,['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <input class="form-control" required type="text" name="sender_id_or_email_or_phone" placeholder="{{ __('Driver Email , Phone Or Id') }}">
                                                                                </div>
                                                                                <div class="from-group">
                                                                                    <input class="form-control" required type="text" name="invitation_code" placeholder="{{ __('Invitation Code') }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                        </div>





                                                        <div class="dropdown ml-2 address">
                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Rating') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#view-received-rating-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View :page',['page'=>__('Received Rating')]) }} </a>
                                                                <a data-toggle="modal" data-target="#view-sent-rating-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View :page',['page'=>__('Sent Rating')]) }} </a>


                                                            </div>

                                                            <div class="modal fade" id="view-received-rating-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">{{ __('View Received Rating For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Client Name') }}</th>
                                                                                        <th>{{ __('Rate') }}</th>
                                                                                        <th>{{ __('Date') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->getReceivedRatings() as $index=>$rate)
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ getModelByNamespaceAndId($rate->author_type,$rate->author_id) }}</td>
                                                                                        <td>{{ $rate->rating }}</td>
                                                                                        <td>{{ formatForView($rate->created_at) }}</td>
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






                                                            <div class="modal fade" id="view-sent-rating-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">{{ __('View Sent Rating For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Client Name') }}</th>
                                                                                        <th>{{ __('Rate') }}</th>
                                                                                        <th>{{ __('Date') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->getSentRatings() as $index=>$rate)
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ getModelByNamespaceAndId($rate->reviewrateable_type,$rate->reviewrateable_id) }}</td>
                                                                                        <td>{{ $rate->rating }}</td>
                                                                                        <td>{{ formatForView($rate->created_at) }}</td>
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
                                                        </div>




                                                        <div class="dropdown ml-2 inventation-codes">

                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Emergency Contacts') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#view-emergency-contacts-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View :page',['page'=>__('Contacts')]) }} </a>
                                                                <a data-toggle="modal" data-target="#add-emergency-contact-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Add :page',['page'=>__('Contacts')]) }} </a>
                                                            </div>
                                                            <div class="modal fade" id="view-emergency-contacts-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">{{ __('View Emergency Contacts For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Emergency Call Name') }}</th>
                                                                                        <th>{{ __('Phone') }}</th>
                                                                                        <th>{{ __('Email') }}</th>
                                                                                        <th>{{ __('Can Receive Travel Info') }}</th>
                                                                                        <th>{{ __('Date') }}</th>
                                                                                        <th>{{ __('Actions') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->emergencyContacts as $index=>$emergencyContact)
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ $emergencyContact->getName() }}</td>
                                                                                        <td>{{ $emergencyContact->getPhone() }}</td>
                                                                                        <td>{{ $emergencyContact->getEmail() }}</td>
                                                                                        <td class="form-group pb-1">
                                                                                            <x-form.checkbox-element data-toggle-route="{{ $toggleCanReceiveTravelInfos }}" data-model-id="{{ $model->id }}" data-model-type="Driver" data-id="{{ $emergencyContact->id }}" class="switch-trigger-js" :is-required="false" :name="'can_receive_travel_info'" :is-checked="$emergencyContact->pivot->can_receive_travel_info"> </x-form.checkbox-element>
                                                                                        </td>
                                                                                        <td>{{ formatForView($emergencyContact->pivot->created_at) }}</td>
                                                                                        <td class="d-flex align-items-center justify-content-sm-center">
                                                                                            @if($user->can(getPermissionName('update')))
                                                                                            <a href="{{route('emergency-contacts.edit',['emergency_contact'=>$emergencyContact->id])}}" class="btn btn-primary btn-sm mr-2">
                                                                                                <i class="la la-pencil"></i>
                                                                                            </a>
                                                                                            @endif
                                                                                            @if($user->can(getPermissionName('delete')))
                                                                                            <a href="#" data-toggle="modal" data-target="#detach-{{ $model->id }}emergency-contacts-{{ $emergencyContact->id }}" class="delete-modal-trigger-js btn btn-danger btn-sm">
                                                                                                <i class="la la-trash"></i></a>
                                                                                            <x-modals.delete :model-full-id="'detach-'.$model->id.'emergency-contacts-'.$emergencyContact->id" :full-delete-route="route('detach.modal.emergency-contacts',['model_id'=>$model->id ,'model_type'=>'Driver', 'emergency_contact_id'=>$emergencyContact->id ])" :model-id="$model->id"></x-modals.delete>
                                                                                            @endif
                                                                                        </td>
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



                                                            <div class="modal fade" id="add-emergency-contact-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('emergency-contacts.attach') }}" method="post">
                                                                            @csrf

                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">
                                                                            {{-- <input type="hidden" name="name" value="{{ $model->getFullName() }}"> --}}
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Attach Emergency Contact To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-6" data-model-id="{{$model->id}}">
                                                                                        <x-form.checkbox :class="'toggle-emergency-call-form'" :id="'from_existing_contact'.$model->id" :label="__('From Existing Contacts')" :is-required="false" :name="'from_existing_contact'" :is-checked="false"> </x-form.checkbox>
                                                                                    </div>

                                                                                    <div class="col-md-6 js-toggle-emergency-call-off-{{ $model->id }}" style="display:none">
                                                                                        <x-form.select :is-required="true" :is-select2="true" :options="$emergencyContactsFormatted" :add-new="false" :label="__('Name')" :all="false" name="emergency_contact_id" id="emergency_contact_id{{ $model->id }}" :selected-value="old('emergency_contact_id') "></x-form.select>
                                                                                    </div>
                                                                                </div>

                                                                                @include('admin.emergency-contacts.form',['model'=>null,'class'=>'js-toggle-emergency-call-on-'.$model->id])

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary js-save-by-ajax">{{ __('Attach :item',['item'=>__('Emergency Contacts')]) }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                        </div>














                                                        <div class="dropdown ml-2 inventation-codes">

                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Ban') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#ban-account-popup{{ $model->id }}" class="dropdown-item" href="#">
                                                                    @if($model->isBanned())
                                                                    {{ __('Unban') }}
                                                                    @else
                                                                    {{ __('Ban') }}
                                                                    @endif
                                                                </a>
                                                                <a data-toggle="modal" data-target="#view-ban-history-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Ban History') }} </a>

                                                            </div>
                                                            <div class="modal fade" id="view-ban-history-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">{{ __('Ban History For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Ban Date') }}</th>
                                                                                        <th>{{ __('Reason') }}</th>
                                                                                        <th>{{ __('Banned By') }}</th>
                                                                                        <th>{{ __('Removing Date') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->banHistories as $index=>$ban)
                                                                                    @if($ban)
                                                                                    @endif
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ formatForView($ban->created_at) }}</td>
                                                                                        <td>{{ $ban->comment }}</td>
                                                                                        <td>{{ $ban->created_by_type::getNameById($ban->created_by_id) }}</td>
                                                                                        <td>{{ formatForView($ban->deleted_at) }}</td>
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





                                                        </div>



                                                        <div @if($model->getIsVerified())
                                                            style="display:none"
                                                            @endif

                                                            data-model-id="{{ $model->id }}" class="dropdown send-verification-code-message-js">

                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Send Verification Code') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#send-whatsapp-verification-code-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Whatsapp') }} </a>
                                                                <a data-toggle="modal" data-target="#send-sms-verification-code-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Sms') }} </a>
                                                                <a data-toggle="modal" data-target="#send-email-verification-code-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Email') }} </a>
                                                            </div>
                                                            <div class="modal fade" id="send-whatsapp-verification-code-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.verification.code.through.whatsapp') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">

                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Send Whatsapp Verification Code To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                {{-- <h2>{{ __('Do ') }}</h2> --}}
                                                                                {{-- <textarea name="message" class="form-control" rows="8" type="text" required placeholder="{{ __('Message Text') }}"></textarea> --}}
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade" id="send-sms-verification-code-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.verification.code.through.sms') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">

                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Send Sms Verification Code To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                {{-- <h2>{{ __('Do ') }}</h2> --}}
                                                                                {{-- <textarea name="message" class="form-control" rows="8" type="text" required placeholder="{{ __('Message Text') }}"></textarea> --}}
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="modal fade" id="send-email-verification-code-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.verification.code.through.email') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Send Verification Message By Email To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            {{-- <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <input class="form-control" required type="text" name="subject" placeholder="{{ __('Message Subject') }}">
                                                                    </div>
                                                                    <div class="from-group">
                                                                        <textarea name="message" class="form-control" rows="8" type="text" required placeholder="{{ __('Message Text') }}"></textarea>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                    <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                </div>

                                                                </form>
                                                            </div>
                                                        </div>
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
<script>
    $('.datatable-js').dataTable({
        "paging": true
        , "dom": 'rtip'
        , "pageLength": 10
    });

</script>
<script>
    $('button[data-dismiss-modal="inner-modal"]').click(function() {
        $('.inner-modal').modal('hide');
    });

</script>
@endsection
