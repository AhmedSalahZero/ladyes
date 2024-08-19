@extends('admin.layouts.app')
@section('title','Admin')
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
                                        <form class="form" action="{{$route}}" method="post"
                                              enctype="multipart/form-data">
											  @if(isset($model))
											  @method('put')
											  @endif 
                                            @csrf
                                            <div class="form-body">
                                                <h4 class="form-section"></h4>
												<div class="row">
													<div class="col-md-12">
                                                        <x-form.input :id="'role'" :label="__('Role')" :type="'text'" :name="'name'" :is-required="true"  :model="$model??null" :placeholder="__('Please Enter :attribute' , ['attribute'=>__('Role Name')])"></x-form.input>
													</div>
												</div>
                                               
                                                <div class="row">
													@foreach(getPermissions() as $permissionArray)
													<div class="col-md-6">
													{{-- {{ dd($permissionArray['title']) }} --}}
                                                        <x-form.checkbox :id="convertStringToClass($permissionArray['name'])"  :label="$permissionArray['title']"  :is-required="true" :name="'permissions['. $permissionArray['name'] .']'" :is-checked="isset($model)&&$model->hasPermissionTo($permissionArray['name']) "> </x-form.checkbox>
													</div>
													@endforeach 
                                                   
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
