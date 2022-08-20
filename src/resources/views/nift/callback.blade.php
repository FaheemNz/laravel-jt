<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Brring</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #202223;
            color: #fff;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
            font-variant: small-caps
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body style="background: url({{asset('images/defaults/home_bg.gif')}}) no-repeat; background-size: cover">
<div class="flex-center position-ref full-height" style="background-color: rgba(0,0,0,0.9)">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="w-50 mx-auto ">
            <div class="card card-border-color card-border-color-primary">
                <div class="text-center">
                    <div class="card-header">
                        <img class="logo-img" src="{{asset('images/defaults/logo.png')}}" alt="logo" width="200"></div>
                </div>
                <div class="card-body">
                    <div class="w-75 mx-auto">
                        <div class="alert {{$data->status == '000' ? 'alert-success' : 'alert-danger'}}   d-flex justify-content-center align-items-center alert-dismissible" role="alert">
                            <div class="icon"><span class="mdi mdi-check"></span></div>
                            <div class="message text-center text-warning">
                                <h4> <b>Transaction {{$data->status != '000' ? 'Dropped': 'Completed'}}</b> </h4>
                                <small class="text-muted">{{$data->message}}</small>
                            </div>
                        </div>
                        @if($data->status == '000')
                            <h4>
                                <table class="table table-sm table-hover table-bordered table-striped">
                                    <tr>
                                        <th>Order ID</th>
                                        <td style="text-align: right"> {{$data->order_id}}</td>
                                    </tr>
                                    <tr>
                                        <th> Ref. No.</th>
                                        <td style="text-align: right">{{$data->refNo}}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount</th>
                                        <td style="text-align: right">PKR {{number_format($data->amount, 2)}}</td>
                                    </tr>
                                </table>
                            </h4>
                        @endif
                    </div>
                    <h3 class="pt-4 pb-4 text-center"> <a href="mailto:support@brrring.com">support@brrring.com</a></h3>
                    <div class="form-group text-center mt-2">
                        <a class="btn btn-primary btn-xl" href="/">Back to Brrring</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
