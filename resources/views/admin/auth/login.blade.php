@extends('admin.layouts.login')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-md-4 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 m-0">
                                <div class="card-header border-0">
                                    <div class="card-title text-center">
                                        <div class="p-1">
                                            <img src="{{asset('public/assets/images/logo.png')}}"
                                                 style="width: 150px;"
                                                 alt="branding logo">
                                        </div>
                                    </div>
                                    <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                        <span>{{__('msg.site_name')}}</span>
                                    </h6>
                                </div>
                                @if(Session::has('error'))
                                    <div class="row mr-2 ml-2" >
                                        <button type="text" class="btn btn-lg btn-block btn-outline-danger mb-2"
                                                id="type-error">{{Session::get('error')}}
                                        </button>
                                    </div>
                                @endif
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form-horizontal form-simple" action="{{route('admin.login')}}"
                                              method="post"
                                              >
                                            @csrf
                                            <fieldset class="form-group position-relative has-icon-left mb-0">
                                                <input type="email" class="form-control form-control-lg input-lg"
                                                       name="email"
                                                       value="{{old('email')}}"
                                                       id="user-name" placeholder="{{__('msg.enter_email')}}"
                                                       required>
                                                <div class="form-control-position">
                                                    <i class="ft-user"></i>
                                                </div>
                                                @error('email')
                                                    <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </fieldset>
                                            <fieldset class="form-group position-relative has-icon-left" style="margin-top: 4px">
                                                <input type="password" class="form-control form-control-lg input-lg"
                                                       name="password"
                                                       id="user-password"
                                                       placeholder="{{__('msg.enter_password')}}" required>
                                                <div class="form-control-position">
                                                    <i class="la la-key"></i>
                                                </div>
                                                @error('password')
                                                    <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </fieldset>
                                            <div class="form-group row">
                                                <div class="col-md-6 col-12 text-center text-md-left">
                                                    <fieldset>
                                                        <input type="checkbox" id="remember-me" class="chk-remember">
                                                        <label for="remember-me">{{__('msg.remember_me')}}</label>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            @error('active')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                            <button type="submit" class="btn btn-info btn-lg btn-block"
                                            style="background-color: #ff7a7f !important;border-color: #ff7a7f !important;"
                                            ><i class="ft-unlock"></i> {{__('msg.login')}}</button>
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
