@extends('admin.layouts.app')
@section('title','Contact us')
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
                                        href="{{route('admin.contacts.index')}}">{{__('msg.contact_us')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('msg.show_message')}}
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
                                @if(admin()->check_route_permission('admin.contacts.show') == 1)
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-form">{{$contact->name ?? ''}}</h4>
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
                                    @if($contact)
                                        <div class="card-body">
                                        <form class="form" action="{{route('admin.contacts.update',$contact->id)}}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">
                                                <h4 class="form-section"></h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p>{{__('msg.name')}} : {{$contact->name}}</p>
                                                        <p class="mt-2">{{__('msg.email')}} : {{$contact->email}}</p>
                                                        <p class="mt-2">{{__('msg.message')}} : {{$contact->message}}</p>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control"
                                                                   for="sort_id">{{__('msg.is_read')}}</label>
                                                            <div class="col-md-9">
                                                                <div class="float-left">
                                                                    <input type="checkbox" name="is_read"
                                                                           class="switchBootstrap" id="switchBootstrap2"
                                                                           {{$contact->is_read == 1 ? 'checked' : ''}}/>
                                                                </div>
                                                                @error('active')
                                                                <span class="text-danger">{{$message}}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="form-actions">
                                                    <a href="{{route('admin.contacts.index')}}" type="button" class="btn btn-warning mr-1 block-page"
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
                                    </div>
                                    @else
                                        <p class="alert alert-danger">{{__('msg.not_found')}}</p>
                                    @endif
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
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/editors/summernote.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/editors/theme/monokai.css')}}">
    <style>
        .note-editor.note-frame.panel.panel-default{
            width: 100%;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('public/assets/js/file_upload.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/editors/summernote/summernote.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                tabsize: 2,
                height: 300,
            });
        })

    </script>
@endsection
