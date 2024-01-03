<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="mhasaballah439@gmail.com">
    <title>Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('public/assets/images/logo.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
          rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css"
          rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css-rtl/vendors.css')}}">
    <!-- END VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/forms/icheck/custom.css')}}">
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css-rtl/app.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css-rtl/custom-rtl.css')}}">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css-rtl/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css-rtl/core/colors/palette-gradient.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css-rtl/pages/login-register.css')}}">
    @yield('styles')
</head>
<body class="vertical-layout vertical-menu 1-column   menu-expanded blank-page blank-page"
      data-open="click" data-menu="vertical-menu" data-col="1-column">
@yield('content')

<script src="{{asset('public/assets/vendors/js/vendors.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"
        type="text/javascript"></script>
<script src="{{asset('public/assets/js/core/app-menu.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/js/core/app.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/js/scripts/forms/form-login-register.js')}}" type="text/javascript"></script>
</body>
</html>
