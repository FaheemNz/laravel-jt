@extends('layouts.main')
@section('banner')
    <div class="panel-header panel-header-sm"></div>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Reference Lists</h5>
                    </div>
                    <div class="card-body" style="font-variant: small-caps">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="title text-center">Country | State | City | Currency</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <a href="{{route('country.index')}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Countries</h3>
                                                <p class="p-0 m-0 text-right font-weight-bold">View All</p>
                                            </div>
                                            <i class="fa-3x fa fa-globe-americas text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <a href="{{route('state.index')}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">States</h3>
                                                <p class="p-0 m-0 text-right font-weight-bold">View All</p>
                                            </div>
                                            <i class="fa-3x fa fa-map text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <a href="{{route('city.index')}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Cities</h3>
                                                <p class="p-0 m-0 text-right font-weight-bold">View All</p>
                                            </div>
                                            <i class="fa-3x fa fa-city text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <a href="{{route('currency.index')}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Currencies</h3>
                                                <p class="p-0 m-0 text-right font-weight-bold">View All</p>
                                            </div>
                                            <i class="fa-3x fa fa-money-bill text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <hr class="w-75 mx-auto">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="title text-center">Category | Reason | Bank</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <a href="{{route('category.index')}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Categories</h3>
                                                <p class="p-0 m-0 text-right font-weight-bold">View All</p>
                                            </div>
                                            <i class="fa-3x fa fa-list text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <a href="{{route("reason.index")}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Reasons</h3>
                                                <p class="p-0 m-0 text-right font-weight-bold">View All</p>
                                            </div>
                                            <i class="fa-3x fa fa-check-double text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <a href="{{route("banks.index")}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Banks</h3>
                                                <p class="p-0 m-0 text-right font-weight-bold">View All</p>
                                            </div>
                                            <i class="fa-3x fa fa-piggy-bank text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
