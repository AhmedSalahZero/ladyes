@extends('admin.layouts.app')
@section('title','Areas')
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
                                @if(admin()->check_route_permission('admin.countries.edit') == 1)
                                    <div class="card-header">
                                        <h4 class="card-title" id="basic-layout-form">{{$city->name}}</h4>
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
                                        <div class="card-body">
                                            @if($city)
                                                    <div class="form-body">
                                                        <h4 class="form-section"></h4>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button type="button" class="btn btn-info"
                                                                        id="addCityModal"
                                                                        style="margin-bottom: 20px;margin-left: 32px;">
                                                                    {{__('msg.add_area')}}
                                                                </button>
                                                                <div class="col-md-12">
                                                                    <table
                                                                        class="table table-striped table-bordered dom-jQuery-events">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center">{{__('msg.name')}}</th>
                                                                            <th class="text-center">{{__('msg.action')}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="tableCities">
                                                                        @if(isset($city->areas) && count($city->areas) > 0)
                                                                            @foreach($city->areas as $area)
                                                                                <tr class="city_row_{{$area->id}}">
                                                                                    <td class="text-center">
                                                                                        {{$area->name}}
                                                                                    </td>
                                                                                    <td class="d-flex align-items-center justify-content-sm-center">
                                                                                <span href="#" item_id="{{$area->id}}"
                                                                                      class="item_delete mr-1 btn btn-danger">
                                                                                    <i class="la la-trash"></i></span>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
    <div class="modal fade" id="cityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <input type="hidden" id="work_time">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('msg.add_city')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="label-control">{{__('msg.name_ar')}}</label>
                            <input type="text"
                                   id="name_ar"
                                   class="form-control border-primary"
                                   placeholder="{{__('msg.name_ar')}}"
                                   name="name_ar" >

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="label-control">{{__('msg.name_en')}}</label>
                            <input type="text"
                                   id="name_en"
                                   class="form-control border-primary"
                                   placeholder="{{__('msg.name_en')}}"
                                   name="name_en" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('msg.back')}}</button>
                    <button type="button" class="btn btn-primary" id="save_city">{{__('msg.save')}}</button>
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
                    <img src="{{asset('public/assets/images/remove.png')}}">
                    <h5>{{__('msg.confirm_delete_item')}}</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{__('msg.back')}}</button>
                    <button type="button" class="btn btn-outline-danger confirm_delete">{{__('msg.confirm')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('scripts')
    <script src="{{asset('public/assets/vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/tables/datatable/dataTables.buttons.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/tables/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/tables/buttons.print.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/js/scripts/tables/datatables/datatable-advanced.js')}}" type="text/javascript"></script>

    <script>
        $(document).on('click','.item_delete',function (){
            $('#deleteItem').modal('show');
            var item_id = $(this).attr('item_id')
            $(document).on('click','.confirm_delete',function (){
                $.ajax({
                    type : 'post',
                    url: "{{route('admin.countries.delete')}}",
                    data:{
                        '_token' : "{{csrf_token()}}",
                        'id' : item_id
                    },
                    success: function (data) {

                        if (data.status == true) {
                            $('#success_msg').show();
                            $('#deleteItem').modal('hide');
                            $('.city_row_' + data.id).remove();
                        }
                    }, error: function (reject) {

                    }
                })
            })

        });

        $(document).on('click','#addCityModal',function (){
            $('#name_ar').val('');
            $('#name_en').val('');
            $('#cityModal').modal('show');
        });

        $(document).on('click','#save_city',function (){
            var name_ar = $('#name_ar').val();
            var name_en = $('#name_en').val();

                $.ajax({
                    type : 'post',
                    url: "{{route('admin.countries.add_area')}}",
                    data:{
                        '_token' : "{{csrf_token()}}",
                        'name_ar' : name_ar,
                        'name_en' : name_en,
                        'city_id' : {{$city->id}},
                    },
                    success: function (data) {

                        if (data.status == true) {
                            $('#tableCities').append(data.html)
                            $('#cityModal').modal('hide');
                            $('.dataTables_empty').css('display', 'none');
                        }
                    }, error: function (reject) {

                    }
                })

        });
    </script>

@endsection
