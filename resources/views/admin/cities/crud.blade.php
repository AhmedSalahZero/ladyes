@extends('admin.layouts.app')
@section('title',$pageTitle)
@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <x-breadcrumbs.index :items="$breadCrumbs"></x-breadcrumbs.index>
        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="basic-form-layouts">
                <div class="row match-height">
                    <div class="col-md-12">
                        <div class="card">
                            <x-form.crud-page-header :page-title="$pageTitle"></x-form.crud-page-header>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form class="form" action="{{$route}}" method="post" enctype="multipart/form-data">
                                        @if(isset($model))
                                        @method('put')
                                        @endif
                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"></h4>
                                            <div class="row">





                                                                    <div class="col-12 mb-4">
                                                                        @include('components.map.maps',[
                                                                        'mapHeight'=>'500px',
                                                                        "mapId"=>'map_id',
                                                                        'searchTextField'=>'search_field_id',
                                                                        'latitude'=>'24.0000',
                                                                        'longitude'=>'45.0000'
                                                                        ])
                                                                    </div>

                                                <div class="col-md-6">
                                                    <x-form.input class="map_name" :id="'name_en'" :label="__('English Name')" :type="'text'" :name="'name_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Name')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">

                                                    <x-form.input class="map_name" :id="'name_ar'" :label="__('Arabic Name')" :type="'text'" :name="'name_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Name')])"></x-form.input>
                                                </div>
												
												
												  
												<div class="col-md-6">
                                                                        <x-form.input :value="isset($model) ? $model->getLongitude() : null" class="MapLon" :id="'longitude'" :label="__('Longitude')" :type="'text'" :name="'longitude'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Longitude')])"></x-form.input>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <x-form.input :value="isset($model) ? $model->getLatitude() : null" class="MapLat" :id="'latitude'" :label="__('Latitude')" :type="'text'" :name="'latitude'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Latitude')])"></x-form.input>
                                                                    </div>					




                                                {{--
                                                <div class="col-md-6">
                                                    <x-form.input :id="'price'" :label="__('Main Price')" :type="'numeric'" :name="'price'" step="any" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Main Price')])"></x-form.input>
                                                </div> --}}

                                                <div class="col-md-6">
                                                    <x-form.input :id="'km_price'" :label="__('Km Price')" :type="'numeric'" :name="'km_price'" step="any" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Km Price')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input :id="'minute_price'" :label="__('Minute Price')" :type="'numeric'" :name="'minute_price'" step="any" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Minute Price')])"></x-form.input>
                                                </div> 
												

                                                <div class="col-md-6">
                                                    <x-form.input :id="'operating_fees'" :label="__('Operating Fees')" :type="'operating_fees'" :name="'operating_fees'" step="any" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Operating Fees')])"></x-form.input>
                                                </div>

												<div class="col-md-6">
                                                    <x-form.input :id="'cancellation_fees_for_client'" :label="__('Cancellation Fees For Client')" :type="'numeric'" :name="'cancellation_fees_for_client'" step="any" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Cancellation Fees For Client')])"></x-form.input>
                                                </div>
												
												<div class="col-md-6">
                                                    <x-form.input :id="'cash_fees'" :label="__('Cash Fees')" :type="'numeric'" :name="'cash_fees'" step="any" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Cash Fees')])"></x-form.input>
                                                </div>
												
												<div class="col-md-6">
                                                    <x-form.input :id="'first_travel_bonus'" :label="__('Bonus After First Success Travel')" :type="'numeric'" :name="'first_travel_bonus'" step="any" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Bonus After First Success Travel')])"></x-form.input>
                                                </div>
												


                                                <div class="col-md-6">
                                                    <x-form.select :is-required="true" :is-select2="true" :options="$countriesFormattedForSelect" :add-new="false" :label="__('Country Name')" :all="false" name="country_id" id="country_id" :selected-value="isset($model) ? $model->getCountryId(): old('country_id') "></x-form.select>
                                                </div>

                                                <div class="col-md-12">
                                                    <br>
                                                    <hr>
                                                    <h3 class="font-weight-bold text-black form-label kt-subheader__title small-caps">{{ __('Rush Hours') }}</h3>
                                                    <hr>
                                                    <div class="repeater mt-2">
                                                        <div data-repeater-list="rush_hours">
                                                            @foreach($model  && count($model->rushHours ) ? $model->rushHours : [null] as $rushHour)
                                                            <div data-repeater-item>
                                                                <div class="row position-relative mb-3 border-bottom-repeater">

                                                                  


                                                                    <div class="col-md-6">
                                                                        <x-form.input :id="'start_time'" :label="__('Start Time')" :type="'time'" :name="'start_time'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Start Time')])"></x-form.input>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <x-form.input :id="'end_time'" :label="__('End Time')" :type="'time'" :name="'end_time'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('End Time')])"></x-form.input>
                                                                    </div>


                                                                    {{--
                                                                    <div class="col-md-6">
                                                                        <x-form.input :id="'price'" :label="__('Main Price')" :type="'numeric'" :name="'price'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Main Price')])"></x-form.input>
                                                                    </div> --}}

                                                                    <div class="col-md-6">
                                                                        <x-form.input :id="'km_price'" :label="__('Km Price')" :type="'numeric'" :name="'km_price'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Km Price')])"></x-form.input>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <x-form.input :id="'minute_price'" :label="__('Minute Price')" :type="'numeric'" :name="'minute_price'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Minute Price')])"></x-form.input>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <x-form.input :id="'operating_fees'" :label="__('Operating Fees')" :type="'operating_fees'" :name="'operating_fees'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Operating Fees')])"></x-form.input>
                                                                    </div>
																	
																	<div class="col-md-6">
                                                                        <x-form.input :id="'cancellation_fees_for_client'" :label="__('Cancellation Fees For Client')" :type="'numeric'" :name="'cancellation_fees_for_client'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Cancellation Fees For Client')])"></x-form.input>
                                                                    </div>
																	
																	
																	<div class="col-md-6">
                                                                        <x-form.input :id="'cash_fees'" :label="__('Cash Fees')" :type="'numeric'" :name="'cash_fees'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Cash Fees')])"></x-form.input>
                                                                    </div>
																	
																	<div class="col-md-6">
                                                                        <x-form.input :id="'first_travel_bonus'" :label="__('Bonus After First Success Travel')" :type="'numeric'" :name="'first_travel_bonus'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Bonus After First Success Travel')])"></x-form.input>
                                                                    </div>
																	


                                                                    <div class="col-md-6">
                                                                        <x-form.input :hint="__('One Of ( 1/5, 2/5, 3/5, 4/5, 5/5)')" :id="'percentage'" :label="__('Rush Hour Percentage')" :type="'text'" :name="'percentage'" step="any" :is-required="true" :model="$rushHour??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Rush Hour Percentage')])"></x-form.input>
                                                                    </div>


                                                                    <div class="col-md-2 delete-repeater-btn ">
                                                                        <button data-repeater-delete type="button" class="btn btn-danger btn-sm d-flex justify-content-between align-items-center " style="margin-right:auto">
                                                                            {{ __('Delete') }}
                                                                            <i class="la la-remove"></i>
                                                                        </button>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        <button class="btn btn-info btn-sm d-flex justify-content-between align-items-center" data-repeater-create type="button">
                                                            {{ __('Add Rush Time') }}
                                                            <i class="la la-plus"></i>
                                                        </button>
                                                    </div>

                                                </div>





                                                <x-form.actions-buttons :index-route="$indexRoute"></x-form.actions-buttons>



                                            </div>




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


@push('js')


<script>
    $(document).ready(function() {

        $('.repeater').repeater({
            initEmpty: false
            , defaultValues: {
                'text-input': 'foo'
            }
            , show: function() {
                $(this).slideDown();
            }
            , hide: function(deleteElement) {
                if (confirm("{{ __('Are you sure you want to delete this element?') }}")) {
                    $(this).slideUp(deleteElement);
                }
            }
            , ready: function(setIndexes) {

            },
            // (Optional)
            // Removes the delete button from the first list item,
            // defaults to false.
            isFirstItemUndeletable: true
        })
    });

</script>

@endpush
@endsection
