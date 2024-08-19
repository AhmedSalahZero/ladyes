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
                                                    <x-form.input  class="map_name" :id="'name_en'" :label="__('English Name')" :type="'text'" :name="'name_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Name')])"></x-form.input>
                                                </div>

												<div class="col-md-6">
                                                    <x-form.input class="map_name" :id="'name_ar'" :label="__('Arabic Name')" :type="'text'" :name="'name_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Name')])"></x-form.input>
                                                </div>

												<div class="col-md-6">
                                                    <x-form.input  class="MapLon" :id="'longitude'" :label="__('Longitude')" :type="'text'" :name="'longitude'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Longitude')])"></x-form.input>
                                                </div>

												<div class="col-md-6">
                                                    <x-form.input class="MapLat" :id="'latitude'" :label="__('Latitude')" :type="'text'" :name="'latitude'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Latitude')])"></x-form.input>
                                                </div>

												<div class="col-md-6">
                                                        <x-form.select class="country-updates-cities-js" :is-required="true" :is-select2="true" :options="$countriesFormattedForSelect" :add-new="false" :label="__('Country Name')" :all="false" name="country_id" id="country_id" :selected-value="isset($model) ? $model->getCountryId(): old('country_id') "></x-form.select>
                                                </div>

												<div class="col-md-6">
                                                        <x-form.select :is-required="true" :is-select2="true" :options="$citiesFormattedForSelect" :add-new="false" :label="__('City Name')" :all="false" name="city_id" id="city_id" :selected-value="isset($model) ? $model->getCityId(): old('city_id') "></x-form.select>
                                                </div>

												<div class="col-12">
													@include('components.map.maps',[
														'mapHeight'=>'500px',
														"mapId"=>'map_id',
														'searchTextField'=>'search_field_id',
														'latitude'=>'24.0000',
														'longitude'=>'45.0000'
													])
												</div>

                                                {{-- <div class="col-md-6">
												    <x-form.image-uploader :name="'logo'"  :id="'logo-id'" :imageUploadId="'logo'" :label="__('Logo')" :image="isset($model) && $model->getFirstMedia('logo') ? $model->getFirstMedia('logo')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>

                                                </div> --}}




                                            </div>



                                            <x-form.actions-buttons :index-route="$indexRoute"></x-form.actions-buttons>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
