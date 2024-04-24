<!DOCTYPE html>
<html dir="rtl" class="loading" lang="en" data-textdirection="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title>@yield('title',env('APP_NAME'))</title>
    <link rel="apple-touch-icon" href="">
    <link rel="shortcut icon" type="image/x-icon" href="{{$favIcon}}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700" rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link href="{{ asset('assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('custom/scss/toastr.css') }}" rel="stylesheet" type="text/css" />
<style>
div table.dataTable{
}
</style>
    @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css-rtl/vendors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css-rtl/app.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css-rtl/custom-rtl.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css-rtl/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css-rtl/core/colors/palette-gradient.css')}}">
    @else
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/core/colors/palette-gradient.css')}}">
    @endif

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/toggle/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/toggle/switchery.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('custom/css/global.css')}}">

    @yield('styles')
</head>
<body data-admin-id="{{ auth('admin')->id() }}" data-lang="{{ app()->getLocale() }}" data-dir="{{ app()->getLocale()  }}" data-token="{{ csrf_token() }}" class="vertical-layout vertical-menu 2-columns chat-application  menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">

    {{-- {{ dd(session()->all()) }} --}}
    @include('admin.layouts.nav')
    @include('admin.layouts.sidebar')
    @yield('content')

    <footer class="footer footer-static footer-light navbar-border navbar-shadow">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
            <span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2023 <a class="text-bold-800 grey darken-2" href="mailto:mhasaballah439@gmail.com" target="_blank">نواعم </a>, جميع الحقوق محفوظة لدى تطبيق </span>
            {{-- <span class="float-md-right d-block d-md-inline-blockd-none d-lg-block">صنع يدويا وبرمج بحب <i class="ft-heart pink"></i></span> --}}
        </p>
    </footer>
    <!-- BEGIN VENDOR JS-->
    <script src="{{asset('assets/vendors/js/vendors.min.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN MODERN JS-->
    <script src="{{asset('assets/js/core/app-menu.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/core/app.js')}}" type="text/javascript"></script>

    <script src="{{asset('assets/js/scripts/customizer.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/forms/toggle/bootstrap-switch.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts/forms/switch.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/css/plugins/select2/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('custom/js/global.js') }}"></script>
    @yield('scripts')

    <script>
        let success = document.getElementById('success_msg');
        setTimeout(() => {
            if (success) {
                success.style.display = 'none';
            }
        }, 10000);

    </script>


    <script>
        function reinitializeSelect2() {
			
            let numberOfMulteSelects = $(document).find('select.select2-select').length;
            $(document).find('select.select2-select').each(function(index, value) {

                if ($(this).selectpicker) {
                    $(this).selectpicker({
                        maxOptions: 0
                        , dir: 'rtl'
                        , buttons: ['selectMax', 'disableAll'],
						  noneSelectedText: '{{ __("Nothing Selected") }}',
						selectAllText: "{{ __('Select All') }}",
						deselectAllText: "{{ __('Deselect All') }}"
                    });
                    $(this).data('max-options', 0);
                }


            })

            $(document).find('select.select2-select.select-all').each(function(index, value) {
                let maxOption = maxOptions[index] !== undefined ? maxOptions[index] : 0;
                $(this).selectpicker({
                    maxOptions: maxOption,
                    //   maxOptions: $(this).data('max-options') || $(this).data('max-options') == 0   ? $(this).data('max-options') : window['maxOptions'],
                    buttons: ['selectMax', 'disableAll']
                });
                $(this).data('max-options', maxOption);

                $(this).closest('div[class*="col-md"]').find('.max-options-select').html('[Max:' + maxOption + ']');

            })


        }
        reinitializeSelect2();

    </script>
    <script src="{{ mix('custom/js/app.js') }}"></script>
    <script type="text/javascript" defer src="{{ asset('custom/js/real-time-notification.js') }}"></script>
    @routes
    @stack('js')



    @if(session()->has('fail'))
    <script defer>
        Swal.fire({
            text: "{{ session()->get('fail') }}"
            , icon: 'error'
        })

    </script>
    @endif


    @if(session()->has('success'))
    <script defer>
        Swal.fire({
            text: "{{ session()->get('success') }}"
            , icon: 'success'
            , timer: 2000
            , showCancelButton: false
            , showConfirmButton: false
        })

    </script>
    @endif

<script>
$("button[data-dismiss=modal2]").click(function(){
	$(this).closest('.inner-modal').modal('hide');
});
</script>

</body>
</html>
