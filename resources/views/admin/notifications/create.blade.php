@extends('admin.layouts.app')
@section('title',$pageTitle)
@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <x-breadcrumbs.index :items="$breadCrumbs"></x-breadcrumbs.index>
        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="basic-form-layouts">
                <div class="row match-height">
                    <div class="col-md-12">
                        <div class="card">
                            <x-form.crud-page-header :page-title="$pageTitle"></x-form.crud-page-header>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form class="form" action="{{$route}}" method="post" enctype="multipart/form-data">
                                        @if(isset($model))
                                        @method('put')
                                        @endif
                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"></h4>
                                            <div class="row">
                                                  <div class="col-md-6">
                                                    <x-form.input :id="'title_en'" :label="__('English Title')" :type="'text'" :name="'title_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Title')])"></x-form.input>
                                                </div>
												<div class="col-md-6">
                                                    <x-form.input :id="'title_ar'" :label="__('Arabic Title')" :type="'text'" :name="'title_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Title')])"></x-form.input>
                                                </div>
												 <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'message_en'" :label="__('English Message')" :type="'text'" :name="'message_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Message')])"></x-form.textarea>
                                                </div>
												 <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'message_ar'" :label="__('Arabic Message')" :type="'text'" :name="'message_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Message')])"></x-form.textarea>
                                                </div>
												
												
												<div class="col-md-6">
                                                        <x-form.select :multiple="true" :please-select="false" :is-required="true" :is-select2="true" :options="$clientsFormatted" :add-new="false" :label="__('Clients')" :all="false" name="client_ids[]"  id="clients" :selected-value="old('client_ids') "></x-form.select>
                                                </div>
												
												<div class="col-md-6">
                                                        <x-form.select :multiple="true" :please-select="false" :is-required="true" :is-select2="true" :options="$driversFormatted" :add-new="false" :label="__('Drivers')" :all="false" name="driver_ids[]"  id="drivers" :selected-value="old('driver_ids') "></x-form.select>
                                                </div>
												
                                         
                                            </div>

                                            <x-form.actions-buttons :index-route="$indexRoute"></x-form.actions-buttons>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- @else
                            @include('admin.layouts.alerts.error_perm')
                            @endif --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@push('js')
 
@endpush
@endsection
