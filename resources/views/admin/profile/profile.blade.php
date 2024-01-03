@extends('admin.layouts.app')
@section('title','Profil')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <div id="user-profile">
                    <div class="row">
                        <div class="col-12">
                            <div class="card profile-with-cover">
                                <div class="card-img-top img-fluid bg-cover height-300"
                                     style="background: url('{{asset('public/assets/images/logo.png')}}');"></div>
                                <div class="media profil-cover-details w-100">
                                    <div class="media-left pl-2 pt-2">
                                        <a href="#" class="profile-image">
                                            <img src="{{admin()->avatar}}"
                                                 class="rounded-circle img-border height-100"
                                                 alt="Card image">
                                        </a>
                                    </div>
                                    <div class="media-body pt-3 px-2">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title">{{admin()->name}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card profile-with-cover">
                                <div class="row match-height">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title" id="basic-layout-round-controls">{{__('msg.my_info')}}</h4>
                                            </div>
                                            @include('admin.layouts.alerts.success')
                                            @include('admin.layouts.alerts.errors')
                                            <div class="card-content collapse show">
                                                <div class="card-body">
                                                    <form class="form" method="post"
                                                          action="{{route('admin.update.profile')}}">
                                                        @csrf
                                                        <div class="form-body">
                                                            <div class="form-group">
                                                                <label for="name">{{__('msg.name')}}</label>
                                                                <input type="text" id="name" class="form-control round"
                                                                       placeholder="{{__('msg.name')}}"
                                                                       value="{{admin()->name}}" name="name">
                                                                @error("name")
                                                                <span class="text-danger"> </span>
                                                                @enderror
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="email">{{__('msg.email')}}</label>
                                                                        <input type="text" id="email"
                                                                               class="form-control round"
                                                                               placeholder="{{__('msg.email')}}"
                                                                               value="{{admin()->email}}" name="email">
                                                                        @error("email")
                                                                        <span class="text-danger"> </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="password">{{__('msg.password')}}</label>
                                                                        <input type="password" id="password"
                                                                               class="form-control round"
                                                                               placeholder="{{__('msg.password')}}"
                                                                               name="password">
                                                                        @error("password")
                                                                        <span class="text-danger"> </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="form-actions">
                                                            <a href="{{route('dashboard.index')}}" type="button"
                                                               class="btn btn-warning mr-1 block-page">
                                                                <i class="ft-x"></i> {{__('msg.back')}}
                                                            </a>
                                                            <button type="submit" class="btn btn-primary block-page">
                                                                <i class="la la-check-square-o"></i> {{__('msg.update')}}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
