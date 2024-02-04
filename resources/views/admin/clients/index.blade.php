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
                     
                                                  

                                                    <div class="modal fade inner-modal" id="ban-account-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('client.toggle.is.banned') }}" method="post">
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
                                                    <div class="d-flex mt-2  " style="gap:12px;flex-wrap:wrap">


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

                                                        {{-- <div class="dropdown ml-2 inventation-codes">

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
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Send Email Message To :name',['name'=>$model->getFullName($lang)]) }}</h5>
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



                                                        </div> --}}











                                                        <div class="dropdown ml-2 emergency-contacts">

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
                                                                                            <x-form.checkbox-element data-toggle-route="{{ $toggleCanReceiveTravelInfos }}" data-model-id="{{ $model->id }}" data-model-type="{{ $modelType }}" data-id="{{ $emergencyContact->id }}" class="switch-trigger-js" :is-required="false" :name="'can_receive_travel_info'" :is-checked="$emergencyContact->pivot->can_receive_travel_info"> </x-form.checkbox-element>
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
                                                                                            <x-modals.delete :model-full-id="'detach-'.$model->id.'emergency-contacts-'.$emergencyContact->id" :full-delete-route="route('detach.modal.emergency-contacts',['model_id'=>$model->id ,'model_type'=>$modelType, 'emergency_contact_id'=>$emergencyContact->id ])" :model-id="$model->id"></x-modals.delete>
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
