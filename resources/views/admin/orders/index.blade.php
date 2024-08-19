@extends('admin.layouts.app')
@section('title','Orders')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section id="file-export">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                @if(admin()->check_route_permission('admin.orders.index') == 1)
                                <div class="card-header" style="padding-bottom: 0px">
                                    <h4 class="card-title">{{__('msg.orders')}}  <button type="button" class="btn btn-sm btn-info search_class la la-search ml-3"></button></h4>



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
                                {{-- @include('admin.layouts.alerts.success') --}}
                                {{-- @include('admin.layouts.alerts.errors') --}}
                                {{-- @include('admin.layouts.alerts.fail') --}}
                                <div class="card-content collapse show" style="margin-top: -12px">
                                    <div class="card-body card-dashboard">

                                        <div class="mb-2 search_area d-none">
                                                <form method="get">
                                                    <div class="col-md-12 mt-2 d-flex align-items-center justify-content-center">
                                                    <div class="col-md-4">
                                                        <label class="label-control">{{__('msg.user')}}</label>
                                                        <select class="select2 form-control" id="small-select" name="user_id">
                                                            @if($users && count($users) > 0)
                                                                <option value="">{{__('msg.select_user')}}</option>
                                                                @foreach($users as $user)
                                                                    <option value="{{$user->id}}" {{request()->get('user_id') == $user->id ? 'selected' : ''}}>{{$user->name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                        <div class="col-md-4">
                                                        <label class="label-control">{{__('msg.driver')}}</label>
                                                        <select class="select2 form-control" id="small-select" name="driver_id">
                                                            @if($drivers && count($drivers) > 0)
                                                                <option value="">{{__('msg.select_driver')}}</option>
                                                                @foreach($drivers as $driver)
                                                                    <option value="{{$driver->id}}" {{request()->get('driver_id') == $driver->id ? 'selected' : ''}}>{{$driver->name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="label-control">{{__('msg.status')}}</label>
                                                        <select class="select2 form-control" id="small-select" name="status_id">
                                                           <option value="">{{__('msg.select_status')}}</option>
                                                           <option value="1" {{request()->get('status_id') == 1 ? 'selected' : ''}}>{{__('msg.pending')}}</option>
                                                           <option value="2" {{request()->get('status_id') == 2 ? 'selected' : ''}}>{{__('msg.approved')}}</option>
                                                           <option value="3" {{request()->get('status_id') == 3 ? 'selected' : ''}}>{{__('msg.on_way')}}</option>
                                                           <option value="4" {{request()->get('status_id') == 4 ? 'selected' : ''}}>{{__('msg.cancel')}}</option>
                                                        </select>
                                                    </div>
                                                    </div>
                                                        <div class="col-md-12 mt-2 d-flex align-items-center justify-content-center">
                                                    <div class="col-md-4">
                                                        <label class="label-control">{{__('msg.from_date')}}</label>
                                                        <input type="date" name="from_date" class="form-control border-primary" value="{{request()->get('from_date')}}">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="label-control">{{__('msg.to_date')}}</label>
                                                        <input type="date" name="to_date" class="form-control border-primary" value="{{request()->get('to_date')}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit" class="btn btn-primary mt-2">{{__('msg.search')}}</button>
                                                    </div>
                                                    </div>
                                                </form>

                                        </div>
                                            <table class="table table-striped table-bordered table-responsive file-export">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">{{__('msg.user')}}</th>
                                                    <th class="text-center">{{__('msg.status')}}</th>
                                                    <th class="text-center">{{__('msg.driver')}}</th>
                                                    <th class="text-center">{{__('msg.is_payment')}}</th>
                                                    <th class="text-center">{{__('msg.total')}}</th>
                                                    <th class="text-center">{{__('msg.created_at')}}</th>
                                                    <th class="text-center">{{__('msg.action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($orders as $order)
                                                    <tr class="row_{{$order->id}}">
                                                        <td class="text-center">
                                                            #{{$order->code}}
                                                        </td>
                                                        <td class="text-center">{{$order->user->first_name ?? '-'}}  {{$order->user->last_name ?? '-'}}</td>
                                                        <td class="text-center">
                                                            @if(admin()->check_route_permission('admin.orders.update_status') == 1)
                                                            <select class="select2 form-control change_status" id="small-select">
                                                                <option value="1" order_id="{{$order->id}}" {{$order->status_id == 1 ? 'selected' : ''}}>{{__('msg.pending')}}</option>
                                                                <option value="2" order_id="{{$order->id}}" {{$order->status_id == 2 ? 'selected' : ''}}>{{__('msg.approved')}}</option>
                                                                <option value="3" order_id="{{$order->id}}" {{$order->status_id == 3 ? 'selected' : ''}}>{{__('msg.on_way')}}</option>
                                                                <option value="4" order_id="{{$order->id}}" {{$order->status_id == 4 ? 'selected' : ''}}>{{__('msg.cancel')}}</option>
                                                            </select>
                                                            @else
                                                                {{$order->status_name}}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">{{isset($order->driver) && $order->driver->name ? $order->driver->name : '-'}}</td>
                                                        <td class="text-center">{{$order->is_payment == 1 ? ' مدفوع':'غير مدفوع'}}</td>
                                                        <td class="text-center">{{(float)$order->total}} {{__('msg.sar')}}</td>
                                                        <td class="text-center">{{date('d/m/Y H:i',strtotime($order->created_at))}}</td>
                                                        <td class="d-flex align-items-center justify-content-sm-center">
                                                                @if(admin()->check_route_permission('admin.orders.show') == 1)
                                                            <a href="{{route('admin.orders.show',$order-> id)}}"
                                                               class="block-page btn btn-primary btn-sm"><i
                                                                    class="la la-eye"></i></a>
                                                                @endif
                                                                    @if(admin()->check_route_permission('admin.orders.delete') == 1)
                                                                        <a href="#" item_id="{{$order->id}}" class="delete_item ml-2 btn btn-danger btn-sm">
                                                                            <i class="la la-trash"></i></a>
                                                                @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/selects/select2.min.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/extensions/toastr.css')}}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css-rtl/plugins/extensions/toastr.css')}}"> --}}
    <style>
        .select2-container--default {
            width: 100% !important;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('assets/vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/tables/datatable/dataTables.buttons.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/tables/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/tables/buttons.print.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts/tables/datatables/datatable-advanced.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/forms/select/select2.full.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts/forms/select/form-select2.js')}}" type="text/javascript"></script>
    {{-- <script src="{{asset('assets/vendors/js/extensions/toastr.min.js')}}" type="text/javascript"></script> --}}
    {{-- <script src="{{asset('assets/js/scripts/extensions/toastr.js')}}" type="text/javascript"></script> --}}
    <script>
        $(document).on('click','.delete_item',function (){
            $('#deleteItem').modal('show');
            var item_id = $(this).attr('item_id')
            $(document).on('click','.confirm_delete',function (){
                $.ajax({
                    type : 'post',
                    url: "{{route('admin.orders.delete')}}",
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

        $(document).on('change','.change_status',function (){
            var status_id = $(this).find('option:selected').val();
            var order_id = $(this).find('option:selected').attr('order_id')

                $.ajax({
                    type : 'post',
                    url: "{{route('admin.orders.update_status')}}",
                    data:{
                        '_token' : "{{csrf_token()}}",
                        'status_id' : status_id,
                        'order_id' : order_id,
                    },
                    success: function (data) {

                        if (data.status == true) {
                            $('#type-success').click();
                        }
                    }, error: function (reject) {

                    }
                })
        });
        $(document).on('click','.search_class',function (){
            if($('.search_area').hasClass('d-none'))
                $('.search_area').removeClass('d-none');
            else
                $('.search_area').addClass('d-none');
        });
    </script>
@endsection
