@extends('admin.layouts.app')
@push('css')
<link rel="stylesheet" href="{{ asset('custom/css/model-details.css') }}">

@endpush
@section('title','Admins')

@section('content')

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section id="file-export">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <x-form.index-page-header :create-route="$createRoute" :page-title="$pageTitle"> </x-form.index-page-header>
                            <div class="card-content collapse show" style="margin-top: -12px">
                                <div class="card-body card-dashboard">
                                    <x-tables.basic-table>
                                        <x-slot name="header">
                                            @include('admin.drivers.th')
                                        </x-slot>
                                        <x-slot name="body">
                                            @foreach($models as $model)
                                      @include('admin.drivers.tr')
                                            @endforeach

                                        </x-slot>
                                    </x-tables.basic-table>
                                    @include('admin.helpers.pagination-links')

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<button type="button" class="d-none" id="type-success">Success</button>
@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('scripts')
<script src="{{asset('assets/vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/dataTables.buttons.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/tables/buttons.html5.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/js/tables/buttons.print.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/scripts/tables/datatables/datatable-advanced.js')}}" type="text/javascript"></script>
<script>
    $('.datatable-js').dataTable({
        "paging": true
        , "dom": 'rtip'
        , "pageLength": 10
    });

</script>
<script>
    $('button[data-dismiss-modal="inner-modal"]').click(function() {
        $('.inner-modal').modal('hide');
    });

</script>
@endsection
