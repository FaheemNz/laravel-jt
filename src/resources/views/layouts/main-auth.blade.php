<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="">
  <link rel="icon" type="image/png" href="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <title>
    Brring
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
    name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200|Open+Sans+Condensed:700" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('css/fontawesome.css')}}" crossorigin="anonymous">

  <!-- CSS Files -->
  <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
  <link href="{{asset('css/now-ui-kit.min.css')}}" rel="stylesheet" />
  <link href="{{asset('css/style.css')}}" rel="stylesheet" />
  @yield('css')
</head>

<body class="sidebar-collapse">
  @yield('banner')
  @yield('content')

  @auth
  {{-- @include('partials.footer') --}}
  @endauth
  @include('partials.scripts')
  @yield('script')
  @include('partials.notification')
  @yield('modals')
</body>

</html>