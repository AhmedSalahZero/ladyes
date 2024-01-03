@extends('admin.layouts.app')
@section('title','Coupons')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{route('dashboard.index')}}">{{__('msg.dashboard')}} </a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{route('admin.coupons.index')}}">{{__('msg.coupons')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('msg.coupons')}}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                @if(admin()->check_route_permission('admin.coupons.edit') == 1)
                                    <div class="card-header">
                                        <h4 class="card-title" id="basic-layout-form">{{__('msg.coupons')}}</h4>
                                        <a class="heading-elements-toggle"><i
                                                class="la la-ellipsis-v font-medium-3"></i></a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content collapse show">
                                        <div class="card-body">
                                            @if($coupon)
                                                <form class="form"
                                                      action="{{route('admin.coupons.update',$coupon->id)}}"
                                                      method="post"
                                                      enctype="multipart/form-data" novalidate>
                                                    @csrf
                                                    <div class="form-body">
                                                        <h4 class="form-section"></h4>
                                                        <div class="row container">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="url">{{__('msg.name')}}</label>

                                                                    <input type="text" id="url"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.name')}}"
                                                                           value="{{ $coupon->name }}"
                                                                           name="name">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="url">{{__('msg.code')}}</label>

                                                                    <input type="text" id="url"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.code')}}"
                                                                           value="{{ $coupon->code }}"
                                                                           name="code">
                                                                    @error('code')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="url">{{__('msg.num_use')}}</label>

                                                                    <input type="number"
                                                                           min="1"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.num_use')}}"
                                                                           value="{{ $coupon->num_use }}"
                                                                           name="num_use">
                                                                    @error('num_use')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="url">{{__('msg.discount')}}</label>

                                                                    <input type="number"
                                                                           step="any"
                                                                           min="0"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.discount')}}"
                                                                           value="{{ (float)$coupon->discount }}"
                                                                           name="discount">
                                                                    @error('discount')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="url">{{__('msg.st_date')}}</label>

                                                                    <input type="date"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.st_date')}}"
                                                                           value="{{ $coupon->st_date }}"
                                                                           name="st_date">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="url">{{__('msg.end_date')}}</label>

                                                                    <input type="date"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.end_date')}}"
                                                                           value="{{ $coupon->end_date }}"
                                                                           name="end_date">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="col-md-3 label-control"
                                                                           for="sort_id">{{__('msg.active')}}</label>
                                                                    <div class="col-md-9">
                                                                        <div class="float-left">
                                                                            <input type="checkbox" name="active"
                                                                                   class="switchBootstrap"
                                                                                   id="switchBootstrap2"
                                                                                   {{$coupon->active == 1 ? 'checked' : ''}}/>
                                                                        </div>
                                                                        @error('active')
                                                                        <span class="text-danger">{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-actions">
                                                            <a href="{{route('admin.coupons.index')}}" type="button"
                                                               class="btn btn-warning mr-1 block-page"
                                                               onclick="history.back();">
                                                                <i class="ft-x"></i> {{__('msg.back')}}
                                                            </a>
                                                            <button type="submit" class="btn btn-primary block-page">
                                                                <i class="la la-check-square-o"></i> {{__('msg.save_close')}}
                                                            </button>
                                                            <button type="submit" name="save" value="1"
                                                                    class="btn btn-primary block-page">
                                                                <i class="la la-check-square-o"></i> {{__('msg.save')}}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @else
                                                <p class="alert alert-danger">{{__('msg.not_found')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    @include('admin.layouts.alerts.error_perm')
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
