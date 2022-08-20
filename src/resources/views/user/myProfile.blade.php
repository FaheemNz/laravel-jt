@extends('layouts.main')
@section('banner')
    <div class="panel-header panel-header-sm"></div>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-4 d-flex flex-wrap">
                <div class="card card-user">
                    <div class="image" style="
                        height: 200px;
                        background-image: url('{{asset("images/defaults/profile_bg.gif")}}');
                        background-repeat: no-repeat;
                        background-position: center;
                        background-size: cover;"
                    >
                    </div>
                    <div class="card-body">
                        <div class="author">
                            <div>
                                <label for="image">
                                    <img src="{{$user->avatar}}"
                                         class="avatar border-gray rounded-circle img-fluid shadow"
                                         style="width: 150px; height: 150px; object-fit: cover; cursor: pointer"
                                         alt="Avatar"
                                         id="imgPrev"
                                    >
                                </label>
                                <h5 class="title">{{$user->first_name . " " . $user->last_name}}</h5>
                            </div>
                            <small class="description">
                                {{$user->email}}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 d-flex flex-wrap">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Profile</h5>
                    </div>
                    <div class="card-body p-4">
                        @include("user.edit-form")
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Orders</h4>
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-responsive table-striped table-boffered dataTable dtr-inline data-table" cellspacing="0" width="100%" role="grid" aria-describedby="datatable_info" style="width: 100%;">

                        </table>
                        <table class="customDataTable table table-striped">
                            <thead class="bg-warning text-primary">
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Reward</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->orders as $order)
                                    <tr>
                                        <td><img src="{{asset('images/'.$order->thumbnail)}}" width="100" class="img-fluid" alt="Image"></td>
                                        <td><a href="{{route('orders.show', $order)}}" target="_blank">{{$order->name}}</a></td>
                                        <td>{{$order->currency->symbol}} {{number_format($order->price)}}</td>
                                        <td>{{$order->currency->symbol}} {{number_format($order->reward)}}</td>
                                        <td>
                                            <span class="badge badge-pill {{$order->status == \App\Utills\Constants\OrderStatus::COMPLETED? "bg-success" : "bg-warning"}}">
                                                {{$order->status}}
                                            </span>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Offers</h4>
                    </div>
                    <div class="card-body">
                        <table class="customDataTable table table-striped">
                            <thead class="bg-warning text-primary">
                                <th>Image</th>
                                <th>Order Details</th>
                                <th>Offer Detail</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                            @forelse($user->offers as $offer)
                                @php $offer_order = $offer->order @endphp
                                <tr>
                                    <td><img src="{{asset('images/'.$offer_order->thumbnail)}}" width="100" class="img-fluid" alt="Image"></td>
                                    <td>
                                        <a href="{{route('orders.show',$offer->order_id)}}" target="_blank">
                                            <span class="d-flex align-items-center justify-content-between">
                                                <b>Name:</b> {{$offer_order->name}}
                                            </span>
                                            <span class="d-flex align-items-center justify-content-between">
                                                <b>Price:</b> {{$offer_order->currency->symbol}} {{number_format($offer_order->price)}}
                                            </span>
                                            <span class="d-flex align-items-center justify-content-between">
                                                <b>Reward:</b> {{$offer_order->currency->symbol}} {{number_format($offer_order->reward)}}
                                            </span>
                                            <span class="d-flex align-items-center justify-content-between">
                                                <b>Needed Till:</b> {{\Carbon\Carbon::parse($offer_order->needed_by)->format("M d, Y")}}
                                            </span>
                                            <small class="d-block text-center">Click To View Details</small>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{route('offers.show',$offer->id)}}" target="_blank">
                                             <span class="d-flex align-items-center justify-content-between">
                                                <b>Charges:</b> {{$offer_order->currency->symbol}} {{number_format($offer->service_charges)}}
                                            </span>
                                            <span class="d-flex align-items-center justify-content-between">
                                                <b>Price:</b> {{$offer_order->currency->symbol}} {{number_format($offer->price)}}
                                            </span>
                                            <span class="d-flex align-items-center justify-content-between">
                                                <b>Reward:</b> {{$offer_order->currency->symbol}} {{number_format($offer->reward)}}
                                            </span>
                                            <span class="d-flex align-items-center justify-content-between">
                                                <b>Valid Till:</b> {{\Carbon\Carbon::parse($offer->expiry_date)->format("M d, Y")}}
                                            </span>
                                            <small class="d-block text-center">Click To View Details</small>
                                        </a>
                                    </td>
                                    <td>
                                         <span class="d-flex align-items-center justify-content-between">
                                            <b>Order Status:</b>
                                            <span class="badge badge-pill {{$offer_order->status == \App\Utills\Constants\OrderStatus::COMPLETED? "bg-success" : "bg-warning"}}">
                                                {{$offer_order->status}}
                                            </span>
                                        </span>
                                        <span class="d-flex align-items-center justify-content-between">
                                            <b>Offer Status:</b>
                                            <span class="badge badge-pill {{in_array($offer->status, \App\Utills\Constants\OfferStatus::NEGATIVE)? "bg-danger" : "bg-warning"}}">
                                                {{$offer->status}}
                                            </span>
                                        </span>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Trips</h4>
                    </div>
                    <div class="card-body">
                        <table class="customDataTable table table-striped">
                            <thead class="bg-warning text-primary">
                                <th>From</th>
                                <th>To</th>
                                <th>Arrival Date</th>
                                <th>Created On</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                            @forelse($user->trips as $trip)
                                <tr>
                                    <td>{{$trip->sourceCity->name . ", ". $trip->sourceCity->state->country->name}}</td>
                                    <td>{{$trip->destinationCity->name . ", ". $trip->destinationCity->state->country->name}}</td>
                                    <td>{{\Carbon\Carbon::parse($trip->arrival_date)->format("M d, Y")}}</td>
                                    <td>{{$trip->created_at->format("M d, Y")}}</td>
                                    <td>{!! $trip->status == "inactive"? "<span class='badge badge-pill badge-danger'>In Active</span>" : "<span class='badge badge-pill badge-success'>Active</span>" !!}</td>
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
@endsection
