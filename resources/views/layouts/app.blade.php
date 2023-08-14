<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
@include('partials._head')
<!-- END: Head-->

<!-- BEGIN: Body-->
{{--<script src="{{ mix('js/app.js') }}"></script>--}}
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
@livewireScripts
<body class="vertical-layout vertical-menu-modern navbar-floating footer-static" data-open="click"
    data-menu="vertical-menu-modern" data-col="">
    <!-- BEGIN: Header-->
    @include('partials._head_nav')

    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    @include('partials.menu')
    <!-- END: Main Menu-->
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="p-0 content-wrapper container-xxl">
            <div class="content-header row"></div>
            <div class="content-body">
                @include('partials._messages')
                @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('partials._javascripts')

    @livewireScripts
    @stack('scripts')
</body>
<!-- END: Body-->

</html>
