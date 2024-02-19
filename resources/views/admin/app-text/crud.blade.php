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
