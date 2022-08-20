@extends('layouts.main')
@section('content')
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="title">
                                    Trip #{{$trip->id}}
                                    {{$trip->trashed() ? '<span class="badge badge-danger">Deleted</span>' : '' }}
                                </h5>
                                <span class="badge badge-pill {{$trip->status == "active"? "badge-warning" : "badge-danger"}}"> {{$trip->status == "active"? "Active" : "In-Active"}}</span>
                            </div>
                            <div class="card-body p-5">
                                <form>
                                    <div class="row">
                                        <div class="col-md-4 px-1">
                                            <div class="form-group">
                                                <label>From City</label>
                                                <input type="text" name="from_city_id" id="from_city_id" class="form-control-plaintext" placeholder="From City" value="{{$trip->getCompleteSourceAddressAttribute()}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pl-1">
                                            <div class="form-group">
                                                <label>Destination City</label>
                                                <input type="text" name="destination_city_id" id="destiantion_city_id" class="form-control-plaintext" placeholder="To City" value="{{$trip->getCompleteDestinationAddressAttribute()}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pl-1">
                                            <div class="form-group">
                                                <label>Arrival Date</label>
                                                <input type="date" name="arrival_date" id="arrival_date" class="form-control-plaintext" placeholder="Arrival Date" value="{{$trip->arrival_date}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @php $user = $trip->createdBy @endphp
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-user">
                            <div class="card-body">
                                @if($user)
                                    <div class="author">
                                        <a href="#">
                                            <img class="avatar boffer-gray" src="{{$user->avatar}}" alt="Avatar">
                                            <h5 class="title">{{$user->first_name ." ". $user->last_name}}</h5>
                                        </a>
                                        <p class="description">
                                            {{$user->email}}
                                        </p>
                                    </div>
                                    <p class="description text-center">
                                        {{$user->phone_no}}
                                    </p>
                                    <div class="text-center">
                                        <span><i class='now-ui-icons sport_trophy'></i> 5</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">
                            Offers
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <table class="customDataTable table-striped table">
                            <thead class="bg-warning text-primary">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="10%">Image</th>
                                    <th>Order Details</th>
                                    <th>Offer Details</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody style="font-variant: small-caps">
                                @forelse($trip->offers as $offer)
                                    <tr>
                                        <td>{{$offer->id}}</td>
                                        <td>
                                            <img src="{{asset("images/".$offer->order->thumbnail)}}" class="img-fluid" alt="Image">
                                        </td>
                                        <td>
                                            <div>
                                                <a href="{{route('orders.show',$offer->order_id)}}" target="_blank">
                                                    <span class="d-flex align-items-center justify-content-between">
                                                        <b>Name:</b> {{$offer->order->name}}
                                                    </span>
                                                    <span class="d-flex align-items-center justify-content-between">
                                                        <b>Price:</b> {{$offer->order->currency->symbol}} {{number_format($offer->order->price)}}
                                                    </span>
                                                    <span class="d-flex align-items-center justify-content-between">
                                                        <b>Reward:</b> {{$offer->order->currency->symbol}} {{number_format($offer->order->reward)}}
                                                    </span>
                                                    <span class="d-flex align-items-center justify-content-between">
                                                        <b>Needed Till:</b> {{\Carbon\Carbon::parse($offer->order->needed_by)->format("M d, Y")}}
                                                    </span>
                                                    <small class="d-block text-center">Click To View Details</small>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="{{route('offers.show',$offer->id)}}" target="_blank">
                                                     <span class="d-flex align-items-center justify-content-between">
                                                        <b>Charges:</b> {{$offer->order->currency->symbol}} {{number_format($offer->service_charges)}}
                                                    </span>
                                                    <span class="d-flex align-items-center justify-content-between">
                                                        <b>Price:</b> {{$offer->order->currency->symbol}} {{number_format($offer->price)}}
                                                    </span>
                                                    <span class="d-flex align-items-center justify-content-between">
                                                        <b>Reward:</b> {{$offer->order->currency->symbol}} {{number_format($offer->reward)}}
                                                    </span>
                                                    <span class="d-flex align-items-center justify-content-between">
                                                        <b>Valid Till:</b> {{\Carbon\Carbon::parse($offer->expiry_date)->format("M d, Y")}}
                                                    </span>
                                                    <small class="d-block text-center">Click To View Details</small>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span>
                                                {{$offer->reason_text}}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-pill {{in_array($offer->status, \App\Utills\Constants\OfferStatus::NEGATIVE)? "badge-danger" : "badge-warning"}} ">
                                                {{$offer->status}}
                                            </span>
                                        </td>

                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
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
@endsection

