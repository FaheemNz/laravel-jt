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
                @if (isset($errors) && $errors->any())
                    <h4>Invalid Link Provided</h4>
                @else
                    <h5>Email Verification Successful</h5>
                @endif

            </div>
            <div class="mt-2 text-center">
                @if (isset($errors) && $errors->any())
                    <img style="height: 200px; width: 200px" src="{{asset('/img/error-cross.png')}}" alt="">
                    <h3 class="mt-3">Please Contact Admin Support</h3>
                @else
                    <img style="height: 200px; width: 200px" src="{{asset('/img/success-check.png')}}" alt="">
                    <h1 class="mt-3">Email Verified Successfully..</h1>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
