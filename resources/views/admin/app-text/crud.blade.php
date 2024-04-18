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

                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"></h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'after_signup_message_en'" :label="__('English Message After Signup')" :type="'text'" :name="'after_signup_message_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Message After Signup')])"></x-form.textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'after_signup_message_ar'" :label="__('Arabic Message After Signup')" :type="'text'" :name="'after_signup_message_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Message After Signup')])"></x-form.textarea>
                                                </div>



                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'travel_end_message_en'" :label="__('English Travel End Message')" :type="'text'" :name="'travel_end_message_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Travel End Message')])"></x-form.textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'travel_end_message_ar'" :label="__('Arabic Travel End Message')" :type="'text'" :name="'travel_end_message_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Travel End Message')])"></x-form.textarea>
                                                </div>


                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'take_safety_en'" :label="__('English Take Safety Description')" :type="'text'" :name="'take_safety_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Take Safety Description')])"></x-form.textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'take_safety_ar'" :label="__('Arabic Take Safety Description')" :type="'text'" :name="'take_safety_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Take Safety Description')])"></x-form.textarea>
                                                </div>


                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'select_your_route_en'" :label="__('English Select Your Route')" :type="'text'" :name="'select_your_route_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Select Your Route')])"></x-form.textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'select_your_route_ar'" :label="__('Arabic Select Your Route')" :type="'text'" :name="'select_your_route_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Select Your Route')])"></x-form.textarea>
                                                </div>



                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'choose_the_appropriate_offer_en'" :label="__('English Choose The Appropriate Offer')" :type="'text'" :name="'choose_the_appropriate_offer_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Choose The Appropriate Offer')])"></x-form.textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'choose_the_appropriate_offer_ar'" :label="__('Arabic Choose The Appropriate Offer')" :type="'text'" :name="'choose_the_appropriate_offer_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Choose The Appropriate Offer')])"></x-form.textarea>
                                                </div>
												
												
												
												
                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'follow_capitan_path_en'" :label="__('English Follow Capitan Path')" :type="'text'" :name="'follow_capitan_path_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Follow Capitan Path')])"></x-form.textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'follow_capitan_path_ar'" :label="__('Arabic Follow Capitan Path')" :type="'text'" :name="'follow_capitan_path_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Follow Capitan Path')])"></x-form.textarea>
                                                </div>
												

                                            </div>


                                            <x-form.actions-buttons :index-route="$indexRoute"></x-form.actions-buttons>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- @else
                            @include('admin.layouts.alerts.error_perm')
                            @endif --}}
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
