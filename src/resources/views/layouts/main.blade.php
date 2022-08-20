<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="">
    <link rel="icon" type="image/png" href="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>
        {{env("APP_NAME")}}
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200|Open+Sans+Condensed:700"
        rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/fontawesome.css')}}" crossorigin="anonymous">

    <!-- CSS Files -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/now-ui-kit-dashboard.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />

    <link href="{{asset('css/style.css')}}" rel="stylesheet" />
    @yield('css')

    @include('partials.sidebar-scripts')
</head>

<body class="sidebar-mini">
    <div class="wrapper">
        @include('partials.sidebar')
        <div class="main-panel" id="main-panel">
            @include('partials.sidebar-nav')
            @yield('banner')
            @yield('content')
            @include('partials.sidebar-setting')
        </div>
    </div>
    @include('partials.notification')
    @yield('modals')

    @yield('script')
</body>

</html>
