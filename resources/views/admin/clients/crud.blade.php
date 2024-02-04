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
                                                    <x-form.input :id="'first_name'" :label="__('First Name')" :type="'text'" :name="'first_name'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('First Name')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'last_name'" :label="__('Last Name')" :type="'text'" :name="'last_name'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Last Name')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.select class="country-updates-cities-js" :is-required="true" :is-select2="true" :options="$countriesFormattedForSelect" :add-new="false" :label="__('Country Name')" :all="false" name="country_id" id="country_id" :selected-value="isset($model) ? $model->getCountryId(): old('country_id') "></x-form.select>
                                                </div>
                                            
                                                <div class="col-md-6">
                                                    <x-form.input :id="'email'" :label="__('Email')" :type="'email'" :name="'email'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Email')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'phone'" :label="__('Phone')" :type="'text'" :name="'phone'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Phone')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.date :id="'birth_date'" :label="__('Birth Date')" :is-required="!isset($model)" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Birth Date')])" :name="'birth_date'" :isRequired="isset($model) ? $model->getBirthDate() : null "> </x-form.date>
                                                </div>
                                                <div class="col-md-6">
                                                    <x-form.checkbox :id="'is_verified'" :label="__('Is Verified')" :is-required="!isset($model)" :name="'is_verified'" :is-checked="isset($model) ? $model->getIsVerified() : true "> </x-form.checkbox>
                                                </div>
                                                <div class="col-md-6">
                                                    <x-form.checkbox :id="'can_pay_by_cash'" :label="__('Can Pay By Cash')" :is-required="!isset($model)" :name="'can_pay_by_cash'" :is-checked="isset($model) ? $model->getCanPayByCash() : true "> </x-form.checkbox>
                                                </div>
												
												
												

                                                <div class="col-md-12">
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <x-form.image-uploader :name="'image'" :id="'image--id'" :label="__('Image')" :image="isset($model) && $model->getFirstMedia('image') ? $model->getFirstMedia('image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                </div>

                                                <div class="col-md-12">
                                                    <hr>
                                                </div>

                                               

                                                {{-- @isset($model)
                                                <div class="col-md-12">
                                                    <hr>
                                                </div>
                                                    <div class="col-md-6">
                                                        <x-form.input :id="'invitation_code'" :label="__('Invitation Code')" :placeholder="__('Please Enter :attribute',['attribute'=>__('Invitation Code')])" :type="'text'" :name="'invitation_code'" :is-required="true" :model="$model??null"></x-form.input>
                                                    </div>
                                                @endisset --}}


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
@endsection
