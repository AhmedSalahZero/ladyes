@extends('admin.layouts.app')
@section('title','Admin')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('dashboard.index')}}">{{__('msg.dashboard')}} </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{route('admin.admins.index')}}">{{__('msg.admins')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('msg.edit_admin')}}
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
                                @if(admin()->check_route_permission('admin.admins.edit') == 1)
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-form">{{__('msg.admin')}}</h4>
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
                                @include('admin.layouts.alerts.success')
                                @include('admin.layouts.alerts.errors')
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">
                                        @if($admin)
                                            <form class="form" action="{{route('admin.admins.update',$admin->id)}}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">
                                                <h4 class="form-section"></h4>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="name">{{__('msg.name')}}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" id="name"
                                                                       class="form-control border-primary"
                                                                       placeholder="Full Name"
                                                                       value="{{ $admin->name}}"
                                                                       name="name" maxlength="20" required>
                                                                @error('name')
                                                                <span class="text-danger">{{$message}}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="email">{{__('msg.email')}}</label>
                                                            <div class="col-md-9">
                                                                <input type="email" id="email"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.email')}}"
                                                                       value="{{ $admin->email}}" name="email" required>
                                                                @error('email')
                                                                <span class="text-danger">{{$message}}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="sort_id">{{__('msg.password')}}</label>
                                                            <div class="col-md-9">
                                                                <input type="password" id="password"
                                                                       class="form-control border-primary"
                                                                       placeholder="{{__('msg.password')}}" name="password">
                                                                @error('password')
                                                                <span class="text-danger">{{$message}}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="sort_id">{{__('msg.active')}}</label>
                                                            <div class="col-md-9">
                                                                <div class="float-left">
                                                                    <input type="checkbox" name="active" class="switchBootstrap" id="switchBootstrap2" {{$admin->active == 1 ? 'checked' : ''}} />
                                                                </div>
                                                                @error('password')
                                                                <span class="text-danger">{{$message}}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 10px">
                                                <div class="col-md-12 col-12">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                    <label>
                                                        {{__('msg.employees')}}
                                                    </label>

                                                    <button type="button" id="create_emp" class="btn btn-info">{{__('msg.create_emp')}}</button>
                                                    </div>
                                                    <table class="table mt-2">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">{{__('msg.name')}}</th>
                                                            <th class="text-center">{{__('msg.email')}}</th>
                                                            <th class="text-center">{{__('msg.active')}}</th>
                                                            <th class="text-center">{{__('msg.action')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="employeesList">
                                                        @if(isset($admin->employees) && count($admin->employees) > 0)
                                                                @foreach($admin->employees as $employee)
                                                                    <tr class="row_{{$employee->id}}">
                                                                        <td class="text-center">{{$employee->name}}</td>
                                                                        <td class="text-center">{{$employee->email}}</td>
                                                                        <td class="text-center">
                                                                            <div class="form-group pb-1">
                                                                                <input type="checkbox" item_id="{{$employee->id}}" name="active"
                                                                                       class="switch active_item" id="switch{{$employee->id}}"
                                                                                    {{$employee->active == 1 ? 'checked' : ''}}/>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <button type="button" item_id="{{$employee->id}}" class="item_delete btn btn-danger">
                                                                                <i class="la la-trash"></i></button>

                                                                            <button type="button"
                                                                               class="block-page ml-2 btn btn-primary item_edit" item_id="{{$employee->id}}"><i
                                                                                    class="la la-pencil"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else

                                                            <p class="alert alert-danger mt-2">{{__('msg.not_employees_found')}}</p>
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                </div>
                                                <div class="form-actions">
                                                    <a href="{{route('admin.admins.index')}}" type="button" class="btn btn-warning mr-1 block-page"
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
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('msg.create_emp')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="alert alert-danger d-none text-center" id="alertDang"></p>
                    <div class="form-group row">
                        <label class="col-md-3 label-control" for="name">{{__('msg.name')}}</label>
                        <div class="col-md-9">
                            <input type="text" id="emp_name"
                                   class="form-control border-primary"
                                   placeholder="Full Name"
                                   value="{{ $admin->name}}"
                                   name="name" maxlength="20" required>

                            <span class="text-danger d-none" id="emp_name_msg">الحقل مطلوب</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 label-control" for="email">{{__('msg.email')}}</label>
                        <div class="col-md-9">
                            <input type="email" id="emp_email"
                                   class="form-control border-primary"
                                   placeholder="{{__('msg.email')}}"
                                   value="{{ $admin->email}}" name="email" required>
                            <span class="text-danger d-none" id="emp_email_msg">الحقل مطلوب</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 label-control" for="sort_id">{{__('msg.password')}}</label>
                        <div class="col-md-9">
                            <input type="password" id="emp_password"
                                   class="form-control border-primary"
                                   placeholder="{{__('msg.password')}}" name="password">
                            <span class="text-danger d-none" id="emp_pass_msg">الحقل مطلوب</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 label-control" for="sort_id">{{__('msg.active')}}</label>
                        <div class="col-md-9">
                            <div class="float-left">
                                <input type="checkbox" name="active" checked class="switchBootstrap" id="switchBootstrap3"/>
                            </div>
                            @error('password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="label-control col-md-3">{{__('msg.permission_group')}}</label>
                        <div class="col-md-9">
                        <select class="select2 form-control" id="permissions_groups" name="permissions_group_id">
                            @if($permissions_groups && count($permissions_groups) > 0)
                                <option value="">{{__('msg.select_permission_group')}}</option>
                                @foreach($permissions_groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        </div>
                    </div>
                    <input type="hidden" id="emp_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('msg.close')}}</button>
                    <button type="button" class="btn btn-primary" id="save_emp">{{__('msg.save')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="deleteItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">{{__('msg.delete_item')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{asset('assets/images/remove.png')}}">
                    <h5>{{__('msg.confirm_delete_item')}}</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{__('msg.back')}}</button>
                    <button type="button" class="btn btn-outline-danger confirm_delete">{{__('msg.confirm')}}</button>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="d-none" id="type-success">Success</button>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/extensions/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css-rtl/plugins/extensions/toastr.css')}}">
    <style>
        .select2-container--default{
            width: 100% !important;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('assets/vendors/js/extensions/toastr.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts/extensions/toastr.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts/forms/select/form-select2.js')}}" type="text/javascript"></script>
    <script>
        $(document).on('click','.item_delete',function (){
            $('#deleteItem').modal('show');
            var item_id = $(this).attr('item_id')
            $(document).on('click','.confirm_delete',function (){
                $.ajax({
                    type : 'post',
                    url: "{{route('admin.admins.delete')}}",
                    data:{
                        '_token' : "{{csrf_token()}}",
                        'id' : item_id
                    },
                    success: function (data) {

                        if (data.status == true) {
                            $('#success_msg').show();
                            $('#deleteItem').modal('hide');
                            $('.row_' + data.id).remove();
                        }
                    }, error: function (reject) {

                    }
                })
            })

        });
        $(document).on('click','.item_edit',function (){
            var item_id = $(this).attr('item_id')
                $.ajax({
                    type : 'post',
                    url: "{{route('edit.emp.admin')}}",
                    data:{
                        '_token' : "{{csrf_token()}}",
                        'emp_id' : item_id
                    },
                    success: function (data) {

                        if (data.status == true) {
                            $('#emp_name').val(data.name);
                            $('#emp_email').val(data.email);
                            $('#emp_id').val(data.id);
                            $("#permissions_groups option[value='" + data.permission_group_id + "']").prop("selected", true);

                            $('.select2').select2()
                            if(data.active == 1)
                            {
                                $('#switchBootstrap3').attr("checked",true)
                                $('.bootstrap-switch-id-switchBootstrap3').removeClass('bootstrap-switch-off')
                                $('.bootstrap-switch-id-switchBootstrap3').addClass('bootstrap-switch-on')
                            }else{
                                $('.bootstrap-switch-id-switchBootstrap3').addClass('bootstrap-switch-off')
                                $('.bootstrap-switch-id-switchBootstrap3').removeClass('bootstrap-switch-on')
                                $('#switchBootstrap3').attr("checked",false)
                            }
                            $('#employeeModal').modal('show');
                            $('#switchBootstrap3').change();
                        }
                    }, error: function (reject) {

                    }
                })

        });
        $(document).on('change','.active_item',function (){
            var item_id = $(this).attr('item_id');
            var active = $(this).is(':checked');
            console.log(active)
            $.ajax({
                type : 'post',
                url: "{{route('admin.admins.update.status')}}",
                data:{
                    '_token' : "{{csrf_token()}}",
                    'id' : item_id,
                    'active' : active,
                },
                success: function (data) {

                    if (data.status == true) {
                        $('#type-success').click();
                    }
                }
            })
        });
        $(document).on('click','#create_emp',function (){
            $('#emp_name').val('');
            $('#emp_email').val('');
            $('#emp_password').val('');
            $('#employeeModal').modal('show')
        });
        $(document).on('click','#save_emp',function (){
            var name = $('#emp_name').val();
            var email = $('#emp_email').val();
            var password = $('#emp_password').val();
            var emp_id = $('#emp_id').val();
            var permission_group_id = $('#permissions_groups :selected').val();
            if (!name) {
                $('#emp_name_msg').removeClass('d-none')
            }else{
                $('#emp_name_msg').addClass('d-none')
            }
            if (!email) {
                $('#emp_email_msg').removeClass('d-none')
            }else{
                $('#emp_email_msg').addClass('d-none')
            }
            if(!emp_id){
                if (!password) {
                    $('#emp_pass_msg').removeClass('d-none')
                }else{
                    $('#emp_pass_msg').addClass('d-none')
                }
            }
            var active = $('#switchBootstrap3').is(':checked');
            var admin_id = {{$admin->id}};
            if(name && email) {
                $.ajax({
                    type: 'post',
                    url: "{{route('store.emp.admin')}}",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'name': name,
                        'email': email,
                        'password': password,
                        'emp_id': emp_id,
                        'active': active,
                        'admin_id': admin_id,
                        'permission_group_id': permission_group_id,
                    },
                    success: function (data) {
                        if (data.status == true) {
                            $('#employeesList').append(data.emp_html);
                            $('#employeeModal').modal('hide')
                            $('.select2').select2();
                            $('.switch:checkbox').checkboxpicker();
                        } else {
                            $('#alertDang').text(data.msg);
                            $('#alertDang').removeClass('d-none');
                        }
                    },
                })
            }
        });
    </script>
@endsection
