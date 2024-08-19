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
                                                    <x-form.input :id="'name'" :label="__('Name')" :type="'text'" :name="'name'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter Name')"></x-form.input>
                                                </div>
                                                <div class="col-md-6">
                                                    <x-form.input :id="'email'" :label="__('Email')" :type="'email'" :name="'email'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter Email')"></x-form.input>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <x-form.input :id="'password'" :label="__('Password')" :type="'password'" :name="'password'" :is-required="!isset($model)" :model="null" :placeholder="__('Please Enter Password')"></x-form.input>
                                                </div>
                                                {{-- {{ dd($errors) }} --}}
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-form.select :is-required="true" :is-select2="true" :options="$rolesFormattedForSelect" :add-new="false" :label="__('Rule Name')" :all="false" name="role_name" id="role_name" :selected-value="isset($model) ? $model->getRoleName(): old('role_name') "></x-form.select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <x-form.checkbox :id="'is-active'" :label="__('Active')" :is-required="!isset($model)" :name="'is_active'" :is-checked="isset($model) ? $model->getIsActive() : true "> </x-form.checkbox>
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
