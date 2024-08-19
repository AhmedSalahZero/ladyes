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
                                                    <x-form.textarea :rows="3" :id="'intro_en'" :label="__('English Intro')" :type="'text'" :name="'app_guideline_into_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Intro')])"></x-form.textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'intro_ar'" :label="__('Arabic Intro')" :type="'text'" :name="'app_guideline_into_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Intro')])"></x-form.textarea>
                                                </div>



                                               






                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'app_guideline_outro_en'" :label="__('English Outro')" :type="'text'" :name="'app_guideline_outro_en'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Outro')])"></x-form.textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <x-form.textarea :rows="3" :id="'app_guideline_outro_ar'" :label="__('Arabic Outro')" :type="'text'" :name="'app_guideline_outro_ar'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Outro')])"></x-form.textarea>
                                                </div>



                                                <div class="col-md-12">
                                                    <hr>
                                                    <br>
                                                    <div class="repeater mt-2">
                                                        <div data-repeater-list="guidelines">
                                                            @foreach($model ? $model->app_guideline_items_ar : [null] as $index=>$arGuideline)
                                                            <div data-repeater-item>
                                                                <div class="row position-relative mb-3 border-bottom-repeater">

                                                                    <div class="col-md-6">
                                                                        <x-form.textarea :rows="3" :id="'app_guideline_en'" :label="__('English Guideline')" :type="'text'" :name="'app_guideline_en'" :is-required="true" :model="null" :value="$model->app_guideline_items_ar[$index]" :placeholder="__('Please Enter :attribute',['attribute'=>__('English Guideline')])"></x-form.textarea>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <x-form.textarea :rows="3" :id="'app_guideline_ar'" :label="__('Arabic Guideline')" :type="'text'" :value="$arGuideline" :is-required="true" :name="'app_guideline_ar'" :model="null" :value="$arGuideline" :placeholder="__('Please Enter :attribute',['attribute'=>__('Arabic Guideline')])"></x-form.textarea>
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
                                                            {{ __('Add Guideline') }}
                                                            <i class="la la-plus"></i>
                                                        </button>
                                                    </div>
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
