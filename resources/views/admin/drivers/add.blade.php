@extends('admin.layouts.app')
@section('title','Drivers')
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
                                        href="{{route('admin.drivers.index')}}">{{__('msg.drivers')}}</a>
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
                                @if(admin()->check_route_permission('admin.drivers.create') == 1)
                                    <div class="card-header">
                                        <h4 class="card-title" id="basic-layout-form">{{__('msg.driver')}}</h4>
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
                                            <form class="form" action="{{route('admin.drivers.store')}}" method="post"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-body">
                                                    <h4 class="form-section"></h4>
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <div class="form-group row"
                                                                 style="justify-content: center;">
                                                                <div class="avatar-upload">
                                                                    <div class="avatar-edit">
                                                                        <input type="file" name="driver_image"
                                                                               id="imageUpload"
                                                                               accept=".png, .jpg, .jpeg"/>
                                                                        <label for="imageUpload"
                                                                               class="imageUploadlabel"><i
                                                                                class="la la-pencil edit_user"></i></label>
                                                                    </div>
                                                                    <div class="avatar-preview"
                                                                         style=" border-radius: 100%;">
                                                                        <div id="imagePreview"
                                                                             style="border-radius: 100%;background-image: url({{asset('assets/images/plus-96.png')}});">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label class="label-control"
                                                                       for="name">{{__('msg.first_name')}}</label>

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
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="label-control"
                                                                       for="name">{{__('msg.last_name')}}</label>

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

                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label class="label-control"
                                                                       for="email">{{__('msg.email')}}</label>

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
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control"
                                                                       for="sort_id">{{__('msg.phone')}}</label>
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
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control"
                                                                       for="sort_id">{{__('msg.password')}}</label>

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
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control"
                                                                       for="sort_id">{{__('msg.id_number')}}</label>

                                                                <input type="text" id="id_number"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.id_number')}}"
                                                                       value="{{ old('id_number') }}" name="id_number"
                                                                >
                                                                @error('id_number')
                                                                <span class="text-danger">{{$message}}</span>
                                                                @enderror
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
                                                                </div>


                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control"
                                                                       for="sort_id">{{__('msg.car_number')}}</label>
                                                                <input type="text" id="car_number"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.car_number')}}"
                                                                       value="{{ old('car_number') }}" name="car_number"
                                                                >
                                                                @error('car_number')
                                                                <span class="text-danger">{{$message}}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 d-flex">
                                                            <div class="col-md-2">
                                                                <input type="radio" checked class="form-control border-primary" value="1" name="car_type">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="33.434" height="13.959" viewBox="0 0 33.434 13.959">
                                                                    <g id="sdvsdv" transform="translate(0.25 -160.073)">
                                                                        <g id="Group_3443" data-name="Group 3443" transform="translate(0 160.323)">
                                                                            <path id="Path_466" data-name="Path 466" d="M91.008,292.623a.481.481,0,0,0,.341-.141l.729-.729a.482.482,0,0,0-.682-.682l-.729.729a.482.482,0,0,0,.341.823Z" transform="translate(-84.702 -281.617)" fill="#262626" stroke="#262626" stroke-width="0.5"/>
                                                                            <path id="Path_467" data-name="Path 467" d="M404.026,292.481a.482.482,0,1,0,.682-.682l-.729-.729a.482.482,0,0,0-.682.682Z" transform="translate(-377.223 -281.616)" fill="#262626" stroke="#262626" stroke-width="0.5"/>
                                                                            <path id="Path_468" data-name="Path 468" d="M31.479,165.8a23.511,23.511,0,0,0-2.7-.94s-4.341-.58-4.946-.589l-3.908-2.72a6.733,6.733,0,0,0-3.858-1.228H11.468a7.2,7.2,0,0,0-3.444.889L5.577,162.55a6.787,6.787,0,0,1-2.344.776l-1.907.261A1.614,1.614,0,0,0,0,165.229V168.6a2.21,2.21,0,0,0,1.472,2.134l2.279.712a3.11,3.11,0,0,0,2.919,2.341,3.028,3.028,0,0,0,2.658-1.692H24.121a3.028,3.028,0,0,0,2.658,1.692,3.36,3.36,0,0,0,0-6.694,3.217,3.217,0,0,0-3.061,3.347,3.653,3.653,0,0,0,.05.6H9.68a3.654,3.654,0,0,0,.05-.6,3.217,3.217,0,0,0-3.061-3.347A3.2,3.2,0,0,0,3.612,170.3l-1.876-.586A1.159,1.159,0,0,1,.965,168.6v-1.58h.549a.53.53,0,0,0,0-1.055H.965v-.732a.585.585,0,0,1,.481-.6l1.907-.261a7.69,7.69,0,0,0,2.656-.879l.22-.12.892.971a2.915,2.915,0,0,0,2.164.98h.995a.53.53,0,0,0,0-1.055H9.285a1.943,1.943,0,0,1-.25-.017l.533-2.575a6.227,6.227,0,0,1,1.9-.3h2.454v2.892h-1.58a.507.507,0,0,0-.482.528c0,.291-2.474.4.482.528s11.344,0,11.344,0c.046,0,4.6.018,7.387,1.432a1.524,1.524,0,0,1,.433.332h-.6a.53.53,0,0,0,0,1.055H31.96c.005.057.009.115.009.173v1.872c0,.523-.21.847-.549.847a.53.53,0,0,0,0,1.055,1.379,1.379,0,0,0,1.093-.541,2.158,2.158,0,0,0,.42-1.361v-1.872a2.807,2.807,0,0,0-1.455-2.517Zm-4.7,2.344a2.3,2.3,0,1,1-2.1,2.292A2.2,2.2,0,0,1,26.78,168.144Zm-20.11,0a2.3,2.3,0,0,1,0,4.583,2.3,2.3,0,0,1,0-4.583Zm1.452-4.259A2.17,2.17,0,0,1,7.8,163.6l-.662-.724,1.315-.719.027-.014Zm6.765-2.507h1.175a5.829,5.829,0,0,1,3.34,1.064l2.627,1.828H14.887Z" transform="translate(0 -160.323)" fill="#262626" stroke="#262626" stroke-width="0.5"/>
                                                                            <path id="Path_469" data-name="Path 469" d="M168.821,280.564a.482.482,0,1,0,0,.965H179.65a.482.482,0,1,0,0-.965Z" transform="translate(-157.511 -272.045)" fill="#262626" stroke="#262626" stroke-width="0.5"/>
                                                                        </g>
                                                                    </g>
                                                                </svg>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="radio" class="form-control border-primary" value="2" name="car_type">
                                                                <svg id="f0c4f1c54ac7a21097f02df20d4af8b9" xmlns="http://www.w3.org/2000/svg" width="35.089" height="14.412" viewBox="0 0 35.089 14.412">
                                                                    <path id="Path_25430" data-name="Path 25430" d="M4.188,27.156a11.746,11.746,0,0,0,0-2.656.732.732,0,0,1,.439-.777L8.06,22.195c4.9-2.518,13.453-1.5,13.81-1.453a23.507,23.507,0,0,1,6.366,2.124,13.336,13.336,0,0,0,3.722,1.071A33.829,33.829,0,0,1,37.767,25.5l.138.05a1.486,1.486,0,0,1,.846,1.021c.282.777.551,2.537.088,3.283a1.225,1.225,0,0,0-.025.8,1.227,1.227,0,0,1-.827,1.435,13.266,13.266,0,0,1-2.381.4,4.048,4.048,0,0,1-7.256.313H14.107a3.829,3.829,0,0,1-6.648-.075c-.125-.044-.326-.113-.564-.188a12.869,12.869,0,0,1-1.228-.432,2.531,2.531,0,0,1-1.416-1.547l-.081-.075v-.182A7.044,7.044,0,0,1,4.188,27.156Zm14.186-5.387A25.262,25.262,0,0,0,9.733,22.84a21.177,21.177,0,0,0,8.641,1.8Zm19.073,4.949-.088-.025c-.558-.194-1.078-.363-1.573-.52a2.263,2.263,0,0,0,.915,1.3,1.724,1.724,0,0,0,1.046.213A2.765,2.765,0,0,0,37.447,26.718ZM31.9,33.659a2.732,2.732,0,1,0-2.776-2.731A2.76,2.76,0,0,0,31.9,33.659Zm-12.275-2.1h8.227A3.9,3.9,0,0,1,29.3,27.883a4.063,4.063,0,0,1,3.716-.783.079.079,0,0,0,.038.006c.006.006.019.006.025.013l.056.019a3.258,3.258,0,0,1,.32.113c.006.006.013.006.025.013a3.928,3.928,0,0,1,1.241.821,1.039,1.039,0,0,0,.081.088c.038.038.075.081.113.125a3.943,3.943,0,0,1,1.015,2.631c0,.094-.013.182-.019.269a10.617,10.617,0,0,0,1.673-.282,2.417,2.417,0,0,1,.182-1.716.854.854,0,0,0,.063-.263c-.088.006-.182.013-.282.013a2.9,2.9,0,0,1-1.466-.382,3.908,3.908,0,0,1-1.7-2.806,19.888,19.888,0,0,0-2.588-.583c-.482-.063-.952-.163-1.416-.269a59.186,59.186,0,0,1-10.564,1.027h-.188Zm0-6.891a58.832,58.832,0,0,0,8.208-.608c-.038-.019-.075-.031-.113-.05a23.193,23.193,0,0,0-6-2.023c-.025,0-.846-.1-2.093-.163ZM10.8,33.446a2.519,2.519,0,1,0-2.563-2.518A2.54,2.54,0,0,0,10.8,33.446ZM5.36,29.9l.05.169a1.331,1.331,0,0,0,.758.9c.194.088.52.194.84.3-.006-.113-.019-.226-.019-.338a3.749,3.749,0,0,1,.633-2.074,3.353,3.353,0,0,1,.476-.583c.031-.031.069-.063.1-.094.05-.044.094-.088.15-.132a.006.006,0,0,1,.006-.006,3.842,3.842,0,0,1,4.7-.157c.031.025.069.038.107.063a3.727,3.727,0,0,1,1.46,3.608h3.753V25.91c-3.659-.125-7.406-.758-10.076-2.449L5.466,24.719a11.255,11.255,0,0,1-.063,2.75A5.9,5.9,0,0,0,5.36,29.9Z" transform="translate(-4 -20.5)"/>
                                                                    <path id="Path_25431" data-name="Path 25431" d="M30.46,31.129a.628.628,0,0,0,.627.626h.99a.626.626,0,1,0,0-1.253h-.99A.628.628,0,0,0,30.46,31.129Z" transform="translate(-14.194 -24.235)"/>
                                                                </svg>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="radio" class="form-control border-primary" value="3" name="car_type">

                                                                <svg xmlns="http://www.w3.org/2000/svg" width="35.237" height="16.523" viewBox="0 0 35.237 16.523">
                                                                    <path id="_883d099d649b728a5b72a01899699ff9" data-name="883d099d649b728a5b72a01899699ff9" d="M34.483,22.688h-2.62a3.309,3.309,0,0,1-6.579,0H10.194a3.309,3.309,0,0,1-6.579,0H.994a.374.374,0,0,1-.374-.374V15.429h0v-.01c0-.02.01-.049.01-.069a.168.168,0,0,1,.01-.069v-.01h0l1.97-4.915a.376.376,0,0,1,.345-.236H19.7a.036.036,0,0,1,.03.01c.02,0,.049.01.069.01s.049.01.069.03a.036.036,0,0,1,.03.01l7.791,4.866h2.856a4.319,4.319,0,0,1,4.314,4.314v2.955A.381.381,0,0,1,34.483,22.688ZM28.573,24.9a2.585,2.585,0,1,0-2.59-2.581A2.594,2.594,0,0,0,28.573,24.9ZM6.9,24.9a2.59,2.59,0,1,0-2.59-2.59A2.594,2.594,0,0,0,6.9,24.9Zm.611-14.035H3.21L1.536,15.055H7.515V10.869Zm5.91,0H8.253v4.186h5.171Zm5.91,0H14.163v4.186h5.171Zm.739.3v3.891H26.3ZM34.108,21.329v-1.97a3.581,3.581,0,0,0-3.575-3.575H1.359v6.156H3.614a3.309,3.309,0,0,1,6.579,0H25.283a3.309,3.309,0,0,1,6.579,0h2.246Z" transform="translate(-0.12 -9.62)" stroke="#000" stroke-width="1"/>
                                                                </svg>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control"
                                                                       for="sort_id">{{__('msg.car_color')}}</label>
                                                                <input type="text" id="car_color"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.car_color')}}"
                                                                       value="{{ old('car_color') }}" name="car_color"
                                                                >
                                                                @error('car_color')
                                                                <span class="text-danger">{{$message}}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control"
                                                                       for="sort_id">{{__('msg.prod_year')}}</label>
                                                                <select class="select2 form-control" name="prod_year">
                                                                    @for($i=date('Y');$i>=2010;$i--)
                                                                        <option value="{{$i}}">{{$i}}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control"
                                                                       for="sort_id">{{__('msg.model_id')}}</label>
                                                                <select class="select2 form-control" name="model_id">
                                                                    @if(count($cars_models) > 0)
                                                                        @foreach($cars_models as $model)
                                                                            <option
                                                                                value="{{$model->id}}">{{$model->name}}</option>
                                                                        @endforeach
                                                                    @endif

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control">{{__('msg.country')}}</label>
                                                                <select class="select2 form-control" id="country" name="country_id">
                                                                    @if($countries && count($countries) > 0)
                                                                        @foreach($countries as $country)
                                                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control">{{__('msg.city')}}</label>
                                                                <select class="select2 form-control" id="city" name="city_id">

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="label-control">{{__('msg.area')}}</label>
                                                                <select class="select2 form-control" id="area" name="area_id">

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label class="label-control"
                                                                       for="id_image">{{__('msg.id_image')}}</label>

                                                                <input type="file"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.id_image')}}"
                                                                       name="id_image"
                                                                >
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label class=" label-control"
                                                                       for="id_image">{{__('msg.driving_license')}}</label>

                                                                <input type="file"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.driving_license')}}"
                                                                       name="driving_license"
                                                                >

                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label class=" label-control"
                                                                       for="id_image">{{__('msg.car_image')}}</label>

                                                                <input type="file"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.car_image')}}"
                                                                       name="car_image"
                                                                >

                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label class=" label-control"
                                                                       for="id_image">{{__('msg.car_front_image')}}</label>

                                                                <input type="file"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.car_front_image')}}"
                                                                       name="car_front_image"
                                                                >

                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label class="label-control"
                                                                       for="id_image">{{__('msg.car_back_image')}}</label>

                                                                <input type="file"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.car_back_image')}}"
                                                                       name="car_back_image">

                                                            </div>
                                                        </div>


                                                    </div>

                                                    <div class="form-actions">
                                                        <a href="{{route('admin.drivers.index')}}" type="button"
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
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/selects/select2.min.css')}}">
    <style>
        .select2-container--default {
            width: 100% !important;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/file_upload.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/forms/select/select2.full.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts/forms/select/form-select2.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function (){
            $('#country').change();
        });
        $(document).on('change', '#country', function () {
            var country_id = $('#country :selected').val();
            $.ajax({
                type: 'post',
                url: "{{route('admin.drivers.country_cities')}}",
                data: {
                    '_token': "{{csrf_token()}}",
                    'country_id': country_id,
                },
                success: function (data) {
                    if (data.status == true) {
                        $('#city')
                            .empty() /*remove all items*/
                            .append(data.city_html)
                        $('#city').change();
                        $('.select2').select2();
                    }
                }
            })
        });
        $(document).on('change', '#city', function () {
            var city_id = $('#city :selected').val();
            $.ajax({
                type: 'post',
                url: "{{route('admin.drivers.country_area')}}",
                data: {
                    '_token': "{{csrf_token()}}",
                    'city_id': city_id,
                },
                success: function (data) {
                    if (data.status == true) {
                        $('#area')
                            .empty() /*remove all items*/
                            .append(data.area_html)

                        $('.select2').select2();
                    }
                }
            })
        });

    </script>
@endsection
