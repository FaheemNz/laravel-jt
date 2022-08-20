@extends('layouts.main')
@section('content')
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a  class="btn btn-primary btn-round pull-right text-white "
                            href="javascript:void(0)"
                            id="btnCreateNewTrip"
                        >
                            Add Counter Offer
                        </a>

                        <h4 class="card-title">Counter Offers</h4>
                        <div class="col-12 mt-2">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Counter Offer Reward</th>
                                        <th>Status</th>
                                        <th>Traveller</th>
                                        <th>Offer Price</th>
                                        <th>Offer Reward</th>
                                        <th class="text-right">Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse($counter_offers as $counter_offer)
                                        <tr>
                                            <td>{{$counter_offer->id}}</td>
                                            <td>
                                                <div class="d-flex align-items-center flex-column text-center">
                                                    <img src="{{$counter_offer->user->avatar}}" style="width: 50px; height: 50px; object-fit: cover" class="rounded-circle img-fluid mb-3" alt="Avatar">
                                                    <p>{{$counter_offer->user->first_name . " " . $counter_offer->user->last_name}}</p>
                                                </div>
                                            </td>
                                            <td>{{$counter_offer->currency->symbol}} {{$counter_offer->reward}}</td>
                                            <td>{{$counter_offer->status}}</td>
                                            <td>{{$counter_offer->offer->user->first_name}} {{$counter_offer->offer->user->last_name}}</td>
                                            <td>{{$counter_offer->offer->currency->symbol}} {{$counter_offer->offer->price}}</td>
                                            <td>{{$counter_offer->offer->currency->symbol}} {{$counter_offer->offer->reward}}</td>
                                            <td class="text-right">
                                                <a href="#" class="btn btn-warning"><i class="now-ui-icons design-2_ruler-pencil text-dark font-weight-bold"></i></a>
                                                <a href="#" class="btn btn-danger"><i class="now-ui-icons ui-1_simple-remove text-light font-weight-bold"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">
                                                <p class="text-muted text-center">No Data To Show</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
