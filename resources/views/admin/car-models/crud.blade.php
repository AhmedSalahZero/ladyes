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
                                                    <x-form.input :id="'name_en'" :label="__('English Name')" :type="'text'" :name="'name_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Name')])"></x-form.input>
                                                </div>
												
												<div class="col-md-6">
                                                    <x-form.input :id="'name_ar'" :label="__('Arabic Name')" :type="'text'" :name="'name_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Name')])"></x-form.input>
                                                </div>
												
												{{-- <div class="col-md-6">
                                                    <x-form.input :id="'manufacturing_year'" :label="__('Manufacturing Year')" :type="'numeric'" :name="'manufacturing_year'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Manufacturing Year')])"></x-form.input>
                                                </div> --}}
												
												<div class="col-md-6">
                                                        <x-form.select :is-required="true" :is-select2="true" :options="$carMakesFormattedForSelect" :add-new="false" :label="__('Car Make')" :all="false" name="make_id" id="make_id" :selected-value="isset($model) ? $model->getMakeId(): old('make_id') "></x-form.select>
                                                </div>
												
                                                <div class="col-md-6">
												    <x-form.image-uploader :name="'logo'"  :id="'logo-id'" :imageUploadId="'logo'" :label="__('Logo')" :image="isset($model) && $model->getFirstMedia('logo') ? $model->getFirstMedia('logo')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
													
                                                </div>
												
												
												 
												
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
