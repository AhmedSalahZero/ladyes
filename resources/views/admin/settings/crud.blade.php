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

                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"></h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <x-form.input :id="'app_name_en'" :label="__('English App Name')" :type="'text'" :name="'app_name_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English App Name')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'app_name_ar'" :label="__('Arabic App Name')" :type="'text'" :name="'app_name_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic App Name')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.image-uploader :required="true" :name="'logo'" :id="'logo'" :label="__('Logo')" :image="isset($model) && $model->getLogo() ? $model->getLogo() : getDefaultImage()"></x-form.image-uploader>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.image-uploader :required="true" :name="'fav_icon'" :id="'fav_icon'" :label="__('Favicon')" :image="isset($model) && $model->getFavIcon() ? $model->getFavIcon() : getDefaultImage()"></x-form.image-uploader>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'phone'" :label="__('Phone')" :type="'text'" :name="'phone'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Phone')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'email'" :label="__('Email')" :type="'email'" :name="'email'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Email')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'facebook_url'" :label="__('Facebook URL')" :type="'text'" :name="'facebook_url'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Facbook URL')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'instagram_url'" :label="__('Instagram URL')" :type="'text'" :name="'instagram_url'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Instagram URL')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'twitter_url'" :label="__('Twitter URL')" :type="'text'" :name="'twitter_url'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Twitter URL')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'youtube_url'" :label="__('Youtube URL')" :type="'text'" :name="'youtube_url'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Youtube URL')])"></x-form.input>
                                                </div>

                                                <div class="col-md-12">
                                                    <hr>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'deduction_percentage'" :label="__('Deduction Percentage'). ' %'" :type="'text'" :name="'deduction_percentage'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Deduction Percentage')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.select :is-required="true" :is-select2="true" :options="$drivingRangeFormatted" :add-new="false" :label="__('Driving Range')" :all="false" name="driving_range" id="driving_range" :selected-value="getSetting('driving_range')"></x-form.select>
                                                </div>
												
												
												  <div class="col-md-6">
                                                    <x-form.input :id="'coupon_discount_percentage'" :label="__('Coupon Discount Percentage'). ' %'" :type="'text'" :name="'coupon_discount_percentage'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Coupon Discount Percentage')])"></x-form.input>
                                                </div>
												




                                                <div class="col-md-12">
                                                    <hr>
                                                </div>

                                                

                                                <div class="col-md-12">
                                                    <hr>
                                                </div>




                                                <div class="col-md-6">
                                                    <x-form.input :id="'WHATSAPP_APP_KEY'" :label="__('Whatsapp App Key')" :type="'text'" :name="'WHATSAPP_APP_KEY'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Whatsapp App Key')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'WHATSAPP_AUTH_KEY'" :label="__('Whatsapp Auth Key')" :type="'text'" :name="'WHATSAPP_AUTH_KEY'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Whatsapp Auth Key')])"></x-form.input>
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
@endsection
