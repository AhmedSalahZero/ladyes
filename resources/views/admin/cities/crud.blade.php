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



                                                <div class="col-md-6">
                                                    <x-form.input class="map_name" :id="'name_en'" :label="__('English Name')" :type="'text'" :name="'name_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Name')])"></x-form.input>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.input class="map_name" :id="'name_ar'" :label="__('Arabic Name')" :type="'text'" :name="'name_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Name')])"></x-form.input>
                                                </div>





                                                <div class="col-md-6">
                                                    <x-form.input :id="'price'" :label="__('Price')" :type="'numeric'" :name="'price'" step="any" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Price')])"></x-form.input>
                                                </div>
												
												 <div class="col-md-6">
                                                    <x-form.input :id="'price'" :label="__('Km Price')" :type="'numeric'" :name="'price'" step="any" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Price')])"></x-form.input>
                                                </div>
												

                                                {{-- <div class="col-md-6">
                                                    <x-form.input :id="'rush_hour_price'" :label="__('Rush Hour Price')" :type="'numeric'" step="any" :name="'rush_hour_price'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Rush Hour Price')])"></x-form.input>
                                                </div> --}}

                                                <div class="col-md-6">
                                                    <x-form.select :is-required="true" :is-select2="true" :options="$countriesFormattedForSelect" :add-new="false" :label="__('Country Name')" :all="false" name="country_id" id="country_id" :selected-value="isset($model) ? $model->getCountryId(): old('country_id') "></x-form.select>
                                                </div>




                                                {{-- <div class="col-md-6">
                                                    <x-form.input class="MapLon"   :id="'longitude'" :label="__('Longitude')" :type="'text'" :name="'longitude'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Longitude')])"></x-form.input>
                                                </div>
												
												<div class="col-md-6">
                                                    <x-form.input class="MapLat" :id="'latitude'" :label="__('Latitude')" :type="'text'" :name="'latitude'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Latitude')])"></x-form.input>
                                                </div> --}}



                                                {{-- <div class="col-12">
													@include('components.map.maps',[
														'mapHeight'=>'500px',
														"mapId"=>'map_id',
														'searchTextField'=>'search_field_id',
														'latitude'=>'24.0000',
														'longitude'=>'45.0000'
													])
												</div>
												 --}}



                                            </div>
											
											

<div class="repeater">
    <!--
        The value given to the data-repeater-list attribute will be used as the
        base of rewritten name attributes.  In this example, the first
        data-repeater-item's name attribute would become group-a[0][text-input],
        and the second data-repeater-item would become group-a[1][text-input]
    -->
    <div data-repeater-list="group-a">
      <div data-repeater-item>
        <input type="text" name="text-input" value="A"/>
        <input data-repeater-delete type="button" value="Delete"/>
      </div>
      <div data-repeater-item>
        <input type="text" name="text-input" value="B"/>
        <input data-repeater-delete type="button" value="Delete"/>
      </div>
    </div>
    <input data-repeater-create type="button" value="Add"/>
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
{{-- <script src="{{ asset('js/repeater.js') }}">

</script>	 --}}


<script>
 $(document).ready(function () {
		console.log($('.repeater').length)
        $('.repeater').repeater({
            // (Optional)
            // start with an empty list of repeaters. Set your first (and only)
            // "data-repeater-item" with style="display:none;" and pass the
            // following configuration flag
            initEmpty: true,
            // (Optional)
            // "defaultValues" sets the values of added items.  The keys of
            // defaultValues refer to the value of the input's name attribute.
            // If a default value is not specified for an input, then it will
            // have its value cleared.
            defaultValues: {
                'text-input': 'foo'
            },
            // (Optional)
            // "show" is called just after an item is added.  The item is hidden
            // at this point.  If a show callback is not given the item will
            // have $(this).show() called on it.
            show: function () {
                $(this).slideDown();
            },
            // (Optional)
            // "hide" is called when a user clicks on a data-repeater-delete
            // element.  The item is still visible.  "hide" is passed a function
            // as its first argument which will properly remove the item.
            // "hide" allows for a confirmation step, to send a delete request
            // to the server, etc.  If a hide callback is not given the item
            // will be deleted.
            hide: function (deleteElement) {
                if(confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            // (Optional)
            // You can use this if you need to manually re-index the list
            // for example if you are using a drag and drop library to reorder
            // list items.
            ready: function (setIndexes) {
              
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
