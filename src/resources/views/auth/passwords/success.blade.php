@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Password Reset Success') }}</div>

                <div class="card-body">
                    <h2 class="text-success">Your Password have been Reset successfully</h2>
                    
                    <div class="text-right">
                        <a href="#" class="btn btn-success">Ok</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
