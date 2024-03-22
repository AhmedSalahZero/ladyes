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
												
												<div class="col-md-6">
                                                        <x-form.select :multiple="true" :please-select="false" :is-required="true" :is-select2="true" :options="$countriesFormatted" :add-new="false" :label="__('Countries')" :all="false" name="country_ids[]"  id="countries" :selected-value="$model->getCountriesIds()"></x-form.select>
                                                </div>
												
                                                <div class="col-md-6">
												    <x-form.image-uploader :name="'logo'"  :id="'logo-id'"  :label="__('Image')" :image="isset($model) && $model->getImage() ? $model->getImage() : getDefaultImage()"></x-form.image-uploader>
													
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
