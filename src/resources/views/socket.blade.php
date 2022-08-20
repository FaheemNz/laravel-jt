{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
    </head>
    <body>

    <script src="{{ asset('/js/app.js') }}"></script>
    <script>
        window.Echo.channel('user-channel-1')
            .listen('OrderStatusToUserEvent', e => {
                console.log(e)
            })
    </script>

    </body>
</html> --}}

<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel Broadcast Redis Socket io Tutorial - ItSolutionStuff.com</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1>Laravel Broadcast Redis Socket io</h1>
            <div id="notification"></div>
        </div>
    </body>

    <script>
            window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT")}}';
    </script>
    <script src="http://192.168.0.169:6001/socket.io/socket.io.js"></script>


    {{-- <script src="{{ url('/js/laravel-echo-setup.js') }}" type="text/javascript"></script> --}}

    <script type="text/javascript">
        // window.Echo.channel('order-11')
        //  .listen('.OrderStatusToUserEvent', (data) => {
        //     console.log('data',data);
        //     $("#notification").append('<div class="alert alert-success">'+data.order_id+'.'+data.status+'</div>');
        // });
            const socket = io();

    </script>


</html>
