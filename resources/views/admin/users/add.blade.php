@extends('admin.layouts.app')
@section('title','Users')
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
                                        href="{{route('admin.users.index')}}">{{__('msg.users')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('msg.create')}}
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
                                @if(admin()->check_route_permission('admin.users.create') == 1)
                                    <div class="card-header">
                                        <h4 class="card-title" id="basic-layout-form">{{__('msg.user')}}</h4>
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
                                            <form class="form" action="{{route('admin.users.store')}}" method="post"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-body">
                                                    <h4 class="form-section"></h4>
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <div class="form-group row" style="justify-content: center;">
                                                                <div class="avatar-upload">
                                                                    <div class="avatar-edit">
                                                                        <input type="file" name="image" id="imageUpload"
                                                                               accept=".png, .jpg, .jpeg"/>
                                                                        <label for="imageUpload"
                                                                               class="imageUploadlabel"><i
                                                                                class="la la-pencil edit_user"></i></label>
                                                                    </div>
                                                                    <div class="avatar-preview" style=" border-radius: 100%;">
                                                                        <div id="imagePreview"
                                                                             style="border-radius: 100%;background-image: url({{asset('public/assets/images/plus-96.png')}});">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-3 label-control"
                                                                       for="name">{{__('msg.first_name')}}</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="first_name"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.first_name')}}"
                                                                           value="{{ old('first_name') }}"
                                                                           name="first_name" required>
                                                                    @error('first_name')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-3 label-control"
                                                                       for="name">{{__('msg.last_name')}}</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="name"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.last_name')}}"
                                                                           value="{{ old('last_name') }}"
                                                                           name="last_name" required>
                                                                    @error('last_name')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-3 label-control"
                                                                       for="email">{{__('msg.email')}}</label>
                                                                <div class="col-md-9">
                                                                    <input type="email" id="email"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.email')}}"
                                                                           value="{{ old('email') }}" name="email"
                                                                           required>
                                                                    @error('email')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-3 label-control"
                                                                       for="sort_id">{{__('msg.phone')}}</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="phone"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.phone')}}"
                                                                           value="{{ old('phone') }}" name="phone"
                                                                           required>
                                                                    @error('phone')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-3 label-control"
                                                                       for="sort_id">{{__('msg.password')}}</label>
                                                                <div class="col-md-9">
                                                                    <input type="password" id="password"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.password')}}"
                                                                           value="{{ old('password') }}" name="password"
                                                                           required>
                                                                    @error('password')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-3 label-control"
                                                                       for="sort_id">{{__('msg.active')}}</label>
                                                                <div class="col-md-9">
                                                                    <div class="float-left">
                                                                        <input type="checkbox" name="active"
                                                                               class="switchBootstrap"
                                                                               id="switchBootstrap2" checked/>
                                                                    </div>
                                                                    @error('password')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-actions">
                                                        <a href="{{route('admin.users.index')}}" type="button"
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
@section('scripts')
    <script src="{{asset('public/assets/js/file_upload.js')}}" type="text/javascript"></script>
@endsection
