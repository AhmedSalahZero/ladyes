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
                                                    <x-form.select class="city-updates-areas-js" :is-required="true" :is-select2="true" :options="$citiesFormattedForSelect" :add-new="false" :label="__('City Name')" :all="false" name="city_id" id="city_id" :selected-value="isset($model) ? $model->getCityId(): old('city_id') "></x-form.select>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.select :is-required="true" :is-select2="true" :options="$areasFormattedForSelect" :add-new="false" :label="__('Area Name')" :all="false" name="area_id" id="area_id" :selected-value="isset($model) ? $model->getAreaId(): old('area_id') "></x-form.select>
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
                                                    <x-form.checkbox :id="'can_receive_orders'" :label="__('Can Receive Orders')" :is-required="!isset($model)" :name="'can_receive_orders'" :is-checked="isset($model) ? $model->getCanReceiveOrders() : true "> </x-form.checkbox>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'id_number'" :label="__('Id Number')" :type="'text'" :name="'id_number'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Id Number')])"></x-form.input>
                                                </div>
                                                <div class="col-md-6">
                                                    <x-form.input :hint="__('Enter ( -1 ) For Using Default Value In Setting')" :id="'deduction_percentage'" :label="__('Deduction Percentage') . ' %'" :type="'text'" :name="'deduction_percentage'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Deduction Percentage')])"></x-form.input>
                                                </div>

                                                <div class="col-md-12">
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <x-form.image-uploader :name="'image'" :id="'image--id'" :label="__('Image')" :image="isset($model) && $model->getFirstMedia('image') ? $model->getFirstMedia('image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                </div>
                                                <div class="col-md-6">
                                                    <x-form.image-uploader :required="true" :name="'id_number_image'" :id="'id_number_image'" :label="__('Id Number')" :image="isset($model) && $model->getFirstMedia('id_number_image') ? $model->getFirstMedia('id_number_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.image-uploader :required="true" :name="'insurance_image'" :id="'insurance_image'" :label="__('Insurance Image')" :image="isset($model) && $model->getFirstMedia('insurance_image') ? $model->getFirstMedia('insurance_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.image-uploader :required="true" :name="'driver_license_image'" :id="'driver_license_image'" :label="__('Driver License Image')" :image="isset($model) && $model->getFirstMedia('driver_license_image') ? $model->getFirstMedia('driver_license_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                </div>

                                                <div class="col-md-12">
                                                    <hr>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.select class="update-models-based-on-make-js" :is-required="true" :is-select2="true" :options="$carMakesFormattedForSelect" :add-new="false" :label="__('Car Make')" :all="false" name="make_id" id="make_id" :selected-value="isset($model) ? $model->getMakeId(): old('make_id') "></x-form.select>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.select :is-required="true" :is-select2="true" :options="$carModelsFormattedForSelect" :add-new="false" :label="__('Car Model')" :all="false" name="model_id" id="model_id" :selected-value="isset($model) ? $model->getModelId(): old('model_id') "></x-form.select>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.select :is-required="true" :is-select2="true" :options="$carSizesFormattedForSelect" :add-new="false" :label="__('Car Size')" :all="false" name="size_id" id="size_id" :selected-value="isset($model) ? $model->getSizeId(): old('size_id') "></x-form.select>
                                                </div>



                                                <div class="col-md-6">
                                                    <x-form.input :id="'manufacturing_year'" :label="__('Manufacturing Year')" :type="'text'" :name="'manufacturing_year'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Manufacturing Year')])"></x-form.input>
                                                </div>


                                                <div class="col-md-6">
                                                    <x-form.input :id="'car_max_capacity'" :label="__('Car Max Capacity')" :type="'numeric'" :name="'car_max_capacity'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Car Max Capacity')])"></x-form.input>
                                                </div>



                                                <div class="col-md-6">
                                                    <x-form.input :id="'plate_letters'" :label="__('Plate Letters')" :type="'text'" :name="'plate_letters'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Plate Letters')])"></x-form.input>
                                                </div>


                                                <div class="col-md-6">
                                                    <x-form.input :id="'plate_numbers'" :label="__('Plate Number')" :placeholder="__('Please Enter :attribute',['attribute'=>__('Plate Number')])" :type="'text'" :name="'plate_numbers'" :is-required="true" :model="$model??null"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'car_color'" :label="__('Car Color')" :placeholder="__('Please Enter :attribute',['attribute'=>__('Car Color')])" :type="'text'" :name="'car_color'" :is-required="true" :model="$model??null"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'car_id_number'" :label="__('Car Id Number')" :placeholder="__('Please Enter :attribute',['attribute'=>__('Car Id Number')])" :type="'text'" :name="'car_id_number'" :is-required="true" :model="$model??null"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.checkbox :id="'has_traffic_tickets'" :label="__('Has Traffic Tickets')" :is-required="!isset($model)" :name="'has_traffic_tickets'" :is-checked="isset($model) ? $model->getHasTrafficTickets() : false "> </x-form.checkbox>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.image-uploader :required="true" :name="'front_image'" :id="'front_image'" :label="__('Front Car Image')" :image="isset($model) && $model->getFirstMedia('front_image') ? $model->getFirstMedia('front_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.image-uploader :required="true" :name="'back_image'" :id="'back_image'" :label="__('Back Car Image')" :image="isset($model) && $model->getFirstMedia('back_image') ? $model->getFirstMedia('back_image')->getFullUrl() : getDefaultImage()"></x-form.image-uploader>
                                                </div>

                                                @isset($model)
                                                <div class="col-md-12">
                                                    <hr>
                                                </div>
                                                    <div class="col-md-6">
                                                        <x-form.input :id="'invitation_code'" :label="__('Invitation Code')" :placeholder="__('Please Enter :attribute',['attribute'=>__('Invitation Code')])" :type="'text'" :name="'invitation_code'" :is-required="true" :model="$model??null"></x-form.input>
                                                    </div>
                                                @endisset


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
