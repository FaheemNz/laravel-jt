@extends('layouts.main-auth')
<style>
    body {
        background-color: #eee !important;
    }
</style>
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="p-2 text-center bg-grey">
                    <img style="height: 50px; width: 150px" src="{{asset('/img/brrring_logo_blue.png')}}" alt="Logo">
                    <br /><br /><br />

                    <h1>BRRRING</h1>
                    <h2>SHOP - TRAVEL - EARN</h2>
                    <h1 class="text-danger">{{$message}}</h1>
                </div>
                <div class="mt-2 text-center">
                    <img style="height: 200px; width: 200px" src="{{asset('/img/error-cross.png')}}" alt="">
                    <h3 class="mt-3">Please Contact Admin Support</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
