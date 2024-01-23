<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Devint">
{{--    <title>@yield('title') - Sidai Africa Limited</title>--}}
    <title>Sokoflow Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">
   <link rel="icon" type="image/png" href="{!! asset('app-assets/images/favicon.png') !!}">
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/vendors/css/vendors.min.css') !!}">
    <!-- END: Vendor CSS-->
{{--   fonts--}}
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/bootstrap.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/bootstrap-extended.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/colors.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/components.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/themes/dark-layout.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/themes/bordered-layout.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/themes/semi-dark-layout.css') !!}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/core/menu/menu-types/vertical-menu.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/plugins/forms/form-validation.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/pages/page-auth.css') !!}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link type="text/css" rel="stylesheet" href="{!! asset('assets/image-uploader/dist/image-uploader.min.css') !!}">
    <!-- END: Page CSS-->

    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/vendors/css/forms/select/select2.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/fonts/fontawesome/css/all.min.css') !!}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @yield('stylesheets')
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/css/style.css') !!}">
    <!-- END: Custom CSS-->
    @livewireStyles
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

        *,
        *::after,
        *::before {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .dashboard-landing {
            height: 100vh;
            width: 100vw;
            background-image: linear-gradient(rgba(0, 0, 0, 0.6),
                    rgba(0, 0, 0, 0.6));
            position: relative;
            background-size: cover;
            background-position: center;
        }

        .dashboard-landing nav {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            padding: 15px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dashboard-landing nav .logo {
            width: 100px;
        }

        .dashboard-landing nav ul li {
            display: inline-block;
            list-style: none;
            margin: 5px 25px;
        }

        .dashboard-landing nav ul li a {
            text-decoration: none;
            color: azure;
        }

        .dashboard-landing .left-side {
            position: absolute;
            height: 100%;
            width: 70%;
            background: rgba(219, 225, 225, 0.484);
            box-shadow: 3px -1px 24px 3px rgba(0, 0, 0, 0.75) inset;
            -webkit-box-shadow: 3px -1px 24px 3px rgba(0, 0, 0, 0.75) inset;
            -moz-box-shadow: 3px -1px 24px 3px rgba(0, 0, 0, 0.75) inset;
        }

        .dashboard-landing .left-side .img-fluid {
            width: 80%;
            height: auto;

        }

            .dashboard-landing .right-side {
                position: absolute;
                height: 100%;
                width: 30%;
                right: 0;
                background: rgba(255, 255, 255, 1);
                box-shadow: -8px -4px 37px 7px rgba(0, 0, 0, 0.75);
                -webkit-box-shadow: -8px -4px 37px 7px rgba(0, 0, 0, 0.75);
                -moz-box-shadow: -8px -4px 37px 7px rgba(0, 0, 0, 0.75);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .dashboard-landing .right-side .login-fields {
                height: 70%;
                width: 100%;
                box-shadow: -8px -4px 37px 7px rgba(255, 255, 255, 0.75) inset;
                -webkit-box-shadow: -8px -4px 37px 7px rgba(255, 255, 255, 0.75)inset;
                -moz-box-shadow: -8px -4px 37px 7px rgba(255, 255, 255, 0.75) inset;
                display: flex;
                align-items: center;
                justify-content: center;

            }

            @media (max-width: 626px) {
            .dashboard-landing .left-side {
                display: none;
                width: 0%;

            }

            .dashboard-landing .right-side {
                    position: absolute;
                    height: 100%;
                    width: 100%;
                    right: 0;
                }
            }
            .title {
                z-index: 999;
                position: absolute;
                overflow: hidden;
                top: 5%;
                left: 40%;
                transform: translate(-50%, -50%);
                font-size: 80px !important;
                -webkit-text-stroke: 1px azure;
                color: rgb(2, 0, 36);
                color: linear-gradient(90deg, rgba(2, 0, 36, 1) 2%, rgba(240, 209, 32, 1) 43%, rgba(53, 130, 123, 1) 100%);
                background-image: linear-gradient(azure, azure);
                background-repeat: no-repeat;
                -webkit-background-clip: text;
                background-clip: text;
                background-position: -700px;
            }

            .login {
                display: inline-block;
                position: relative;
                padding: 7px 18px;
                border-radius: 10px;
                background-color: #39B54A !important;
                box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2) inset;
                display: flex;
                gap: 1px;
            }

            .login span {
                font-size: 24px;
                display: inline-block;
                color: blue;
            }

            @keyframes backcolor {
                100% {
                    background-position: 0px;
                }

            }
        .select2-selection__arrow {
           display: none !important;
        }
    </style>
</head>
