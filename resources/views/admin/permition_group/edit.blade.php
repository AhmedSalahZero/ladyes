@extends('admin.layouts.app')
@section('title','Permission group')
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
                                        href="{{route('admin.permission_group.index')}}">{{__('msg.permission_group')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('msg.edit')}}
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
                                @if(admin()->check_route_permission('admin.permission_group.edit') == 1)
                                    <div class="card-header">
                                        <h4 class="card-title" id="basic-layout-form">{{__('msg.status')}}</h4>
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
                                            @if($group)
                                                <form class="form"
                                                      action="{{route('admin.permission_group.update',$group->id)}}"
                                                      method="post"
                                                      enctype="multipart/form-data" novalidate>
                                                    @csrf
                                                    <div class="form-body">
                                                        <h4 class="form-section"></h4>
                                                        <div class="row">

                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-md-3 label-control">{{__('msg.name')}}</label>
                                                                        <div class="col-md-9">
                                                                            <input type="text"
                                                                                   class="form-control border-primary"
                                                                                   placeholder="{{__('msg.name')}}"
                                                                                   value="{{ $group->name }}"
                                                                                   name="name">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-md-3 label-control"
                                                                               for="sort_id">{{__('msg.is_default')}}</label>
                                                                        <div class="col-md-9">
                                                                            <div class="float-left">
                                                                                <input type="checkbox" name="is_default"
                                                                                       class="switchBootstrap"
                                                                                       id="switchBootstrap2" {{$group->is_default == 1 ? 'checked' : ''}}/>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        {{__('msg.permissions')}}
                                                        <hr>
                                                        <div class="row">


                                                            @foreach ($links as $link)
                                                                <div class="box_permission">
                                                                    <ul>
                                                                        <li>
                                                                            <input type="checkbox" id="option_{{$link->id}}"  class="form-check-input parent" name="permission[]" value="{{$link->id}}"
                                                                           {{$group->is_hav_link($link->id) ? 'checked' : ''}}
                                                                            ><label for="option"> {{$link->name}}</label>

                                                                            <ul>
                                                                                @foreach($link->sub_links_permitions as $child)
                                                                                    <li><label><input class="form-check-input subOption_{{$link->id}}"
                                                                                                      name="permission[]" type="checkbox" value="{{$child->id}}"
                                                                                            {{$group->is_hav_link($child->id) ? 'checked' : ''}}
                                                                                            > {{$child->name}}
                                                                                        </label>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>

                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="form-actions">
                                                            <a href="{{route('admin.permission_group.index')}}" type="button"
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
@section('styles')
    <style>
        .box_permission{
            display: inline-flex;
            flex-direction: column;
            border: 2px solid;
            padding: 15px;
            border-radius: 30px;
            margin: 6px;
        }
        .parent{
            font-size: 16px;
            color: black;
            font-weight: bold;
            margin-bottom: 2%;
            display: flex;
            align-items: center;
        }
        .child{
            display: flex;
            align-items: center;
            margin-right: 10%;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/permitions.js')}}"
@endsection
