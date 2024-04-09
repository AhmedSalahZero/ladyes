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
                                        <input type="hidden" name="discount_type" value="fixed">
                                        @if(isset($model))
                                        @method('put')
                                        @endif
                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"></h4>
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <x-form.select class="model-updates-users-js" :is-required="true" :is-select2="true" :options="[
														['title'=>__('Driver') , 'value'=>'Driver'],
														['title'=>__('Client') , 'value'=>'Client']
													]" :add-new="false" :label="__('User Type')" :all="false" name="model_type" id="model_type" :selected-value="isset($model) ? $model->getModelType(): old('model_type') "></x-form.select>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.select :is-required="true" :is-select2="true" :options="$usersFormattedForSelect" :add-new="false" :label="__('User Name')" :all="false" name="model_id" id="model_id" :selected-value="isset($model) ? $model->getModelId(): old('model_id') "></x-form.select>
                                                </div>

                                                {{-- <div class="col-md-6">
                                                    <x-form.input :id="'name_en'" :label="__('English Name')" :type="'text'" :name="'name_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Name')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'name_ar'" :label="__('Arabic Name')" :type="'text'" :name="'name_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Name')])"></x-form.input>
                                                </div> --}}
                                                {{-- <div class="col-md-6">
                                                        <x-form.select :please-select="false" :is-required="true" :is-select2="true" :options="$discountTypesFormatted" :add-new="false" :label="__('Discount Type')" :all="false" name="discount_type" id="discount_type" :selected-value="isset($model) ? $model->getDiscountType() : old('discount_type') "></x-form.select>
                                                </div> --}}
                                                <div class="col-md-6">
                                                    <x-form.input :id="'amount'" :label="__('Deposit Amount')" :type="'text'" :name="'amount'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Amount')])"></x-form.input>
                                                </div>
												
												 <div class="col-md-6">
                                                    <x-form.select :is-required="true" :is-select2="true" :options="$paymentMethodsFormattedForSelect" :add-new="false" :label="__('Deposit Method')" :all="false" name="payment_method" id="payment_method" :selected-value="isset($model) ? $model->getPaymentMethod(): old('payment_method') "></x-form.select>
                                                </div>

                                                {{-- <div class="col-md-6">
                                                    <x-form.input :value="1" :id="'number_of_uses'" :label="__('Number Of Uses')" :type="'numeric'" :name="'number_of_uses'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Number Of Uses')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.date :hint="__('If Empty This It Will Be Unlimited')" :id="'start_date'" :label="__('Start Date')" :type="'text'" :name="'start_date'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Start Date')])"></x-form.date>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.date :hint="__('If Empty This It Will Be Unlimited')" :id="'end_date'" :label="__('End Date')" :type="'text'" :name="'end_date'" :is-required="false" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('End Date')])"></x-form.date>
                                                </div> --}}



                                                {{-- <div class="col-md-6">
                                                    <x-form.checkbox :id="'is-active'" :label="__('Active')" :is-required="!isset($model)" :name="'is_active'" :is-checked="isset($model) ? $model->getIsActive() : true "> </x-form.checkbox>
                                                </div> --}}
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
