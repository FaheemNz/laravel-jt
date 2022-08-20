@extends('layouts.main')
@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
   <div class="row">
      <div class="col-md-8 ">
         <div class="row">
             <div class="col-md-6 d-flex flex-wrap">
                 <div class="card">
                     <div class="card-header">
                         <div class="d-flex align-items-center justify-content-between">
                             <h5 class="title">Offer #{{$offer->id}} {{$offer->trashed() ? '<span class="badge badge-danger">Deleted</span>' : '' }}</h5>
                             <span class="badge {{$offer->is_disabled? "badge-danger" : "badge-warning"}}">{{$offer->is_disabled? "Disable" : "Active"}}</span>
                         </div>
                         <div class="progress">
                             @if($offer->status == \App\Utills\Constants\OfferStatus::CLOSED)
                                 <div class="progress-bar progress-bar-striped progress-bar-animated active bg-danger" role="progressbar" style="width:0%">
                                     Closed  0%
                                 </div>
                             @elseif($offer->status == \App\Utills\Constants\OfferStatus::OPEN)
                                 <div class="progress-bar progress-bar-striped progress-bar-animated active bg-warning" role="progressbar" style="width:25%">
                                     Open  25%
                                 </div>
                             @elseif($offer->status == \App\Utills\Constants\OfferStatus::PAYMENT_IN_PROGRESS)
                                 <div class="progress-bar progress-bar-striped progress-bar-animated active bg-warning" role="progressbar" style="width:75%">
                                     Payment In Progress  75%
                                 </div>
                             @elseif($offer->status == \App\Utills\Constants\OfferStatus::ACCEPTED)
                                 <div class="progress-bar progress-bar-striped progress-bar-animated active bg-success" role="progressbar" style="width:100%">
                                     Accepted  100%
                                 </div>
                             @elseif($offer->status == \App\Utills\Constants\OfferStatus::REJECTED || $offer->status == \App\Utills\Constants\OfferStatus::UNACCEPTED)
                                 <div class="progress-bar progress-bar-striped progress-bar-animated active bg-danger" role="progressbar" style="width:100%">
                                     {{ucwords($offer->status)}}  100%
                                 </div>
                             @endif

                         </div>
                     </div>
                     <div class="card-body">
                         <div class="row">
                             <div class="col-12">
                                 <h5>Offer Summary</h5>
                                 <span class="d-flex align-items-center justify-content-between  bg-light p-2">
                                <span>Service Charges:</span> <b>{{$offer->order->currency->symbol}} {{number_format($offer->service_charges)}}</b>
                            </span>
                                 <span class="d-flex align-items-center justify-content-between p-2">
                                <span>Demanded Price:</span> <b>{{$offer->order->currency->symbol}} {{number_format($offer->price)}}</b>
                            </span>
                                 <span class="d-flex align-items-center justify-content-between  bg-light p-2">
                                <span>Demanded Reward:</span> <b>{{$offer->order->currency->symbol}} {{number_format($offer->reward)}}</b>
                            </span>
                                 <span class="d-flex align-items-center justify-content-between p-2">
                                <span>Valid Till:</span> <b>{{\Carbon\Carbon::parse($offer->expiry_date)->format("M d, Y")}}</b>
                            </span>
                                 <p class="mt-2">{{$offer->description}}</p>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col-md-6 d-flex flex-wrap">
                 @php $trip = $offer->trip @endphp
                 <div class="card">
                     <div class="card-header">
                         <div class="d-flex align-items-center justify-content-between">
                             <h5 class="title"><a href="{{route("trips.show", $offer->trip_id)}}">Trip #{{$offer->trip_id}}</a></h5>
                             <span class="badge badge-pill {{$trip->status == "active"? "badge-warning" : "badge-danger"}}"> {{$trip->status == "active"? "Active" : "In-Active"}}</span>
                         </div>
                     </div>
                     <div class="card-body">
                         <div class="row">
                             <div class="col-12">
                                 <h5>Trip Summary</h5>
                                 <span class="d-flex align-items-center justify-content-between  bg-light p-2">
                                        <span>From:</span> <b>{{$trip->getCompleteSourceAddressAttribute()}}</b>
                                    </span>
                                 <span class="d-flex align-items-center justify-content-between p-2">
                                        <span>To:</span> <b>{{$trip->getCompleteDestinationAddressAttribute()}}</b>
                                    </span>
                                 <span class="d-flex align-items-center justify-content-between  bg-light p-2">
                                        <span>Arrival Date:</span> <b>{{\Carbon\Carbon::parse($trip->arrival_date)->format("M d, Y")}}</b>
                                    </span>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
          <div class="row">
              <div class="col-12">
                  @php
                      $order = $offer->order;
                      $allStatuses = \App\Utills\Constants\OrderStatus::ALL;
                      $currentStatusIndex = array_search($order->status, \App\Utills\Constants\OrderStatus::ALL) + 1;
                      $per = round(ceil(($currentStatusIndex / count($allStatuses)) * 100));
                  @endphp
                  <div class="card">
                      <div class="card-header">
                          <div class="d-flex align-items-center justify-content-between">
                              <h5 class="title"><a href="{{route("orders.show", $order->id)}}">Order #{{$order->id}}</a></h5>
                          </div>
                          <div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated active bg-success" role="progressbar" style="width:{{$per}}%">
                                  {{\Illuminate\Support\Str::title($order->status)}}  {{$per}}%
                              </div>
                          </div>
                      </div>
                      <div class="card-body">
                          <div class="row">
                              <div class="col-12">
                                  <h5>Order Summary</h5>
                                  <span class="badge {{$order->with_box? "badge-warning" : "badge-danger"}}">{{$order->with_box? "With Box" : "Without Box"}}</span>
                                  <span class="badge {{$order->is_doorstep_delivery? "badge-warning" : "badge-danger"}}">{{$order->is_doorstep_delivery? "Doorstep Delivery" : "Will Pickup Myself"}}</span>
                                  @if($order->is_disputed)
                                      <span class="badge badge-danger">Dispute</span>
                                  @endif
                                  @if($order->is_disabled)
                                      <span class="badge badge-danger">Disabled</span>
                                  @endif
                                  <span class="d-flex align-items-center justify-content-between bg-light p-2">
                                        <span>Customer:</span> <b>{{$order->user->first_name}} {{$order->user->last_name}}</b>
                                  </span>
                                  <span class="d-flex align-items-center justify-content-between p-2">
                                        <span>Item Name:</span> <b>{{$order->name}}</b>
                                  </span>
                                  <span class="d-flex align-items-center justify-content-between bg-light p-2">
                                        <span>Category:</span> <b>{{$order->category->name}}</b>
                                  </span>
                                  <span class="d-flex align-items-center justify-content-between p-2">
                                        <span>Needed Till:</span> <b>{{\Carbon\Carbon::parse($order->needed_by)->format("M d, Y")}}</b>
                                  </span>
                                  <span class="d-flex align-items-center justify-content-between bg-light p-2">
                                        <span>Price / Quantity:</span> <b>{{$order->currency->symbol}} {{number_format($order->price)}} <small>({{$order->quantity}})</small></b>
                                  </span>
                                  <span class="d-flex align-items-center justify-content-between p-2">
                                        <span>Reward:</span> <b>{{$order->currency->symbol}} {{number_format($order->reward)}}</b>
                                  </span>
                                  <span class="d-flex align-items-center justify-content-between bg-light p-2">
                                        <span>Weight:</span> <b>{{\App\Utills\Constants\OrderWeight::ALL[$order->weight]}}</b>
                                  </span>
                                  <span class="d-flex align-items-center justify-content-between p-2">
                                        <span>From:</span> <b>{{$order->getCompleteSourceAddressAttribute()}}</b>
                                  </span>
                                  <span class="d-flex align-items-center justify-content-between bg-light p-2">
                                        <span>To:</span> <b>{{$order->getCompleteDestinationAddressAttribute()}}</b>
                                  </span>
                                  @if($order->url)
                                      <span class="d-flex align-items-center justify-content-between p-2">
                                            <span>Link:</span> <b><a href="{{$order->url}}">Web Link</a></b>
                                      </span>
                                  @endif
                                  <p class="mt-2">{{$order->description}}</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-12">
                @include("shared.cards.user-cards", ["user" => $offer->createdBy])
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="card card-user">
                  <div class="card-body p-2">
                     <div class="row">
                         <div class="col-md-12 mb-3">
                             <img src="/images/{{$order->thumbnail}}" class="img-thumbnail" alt="Thumbnail">
                         </div>
                         <div class="col-md-12">
                             <h6 class="title">Order Images</h6>
                             @if($order->images()->where('type','customer')->count() > 0)
                                 @foreach ($order->images()->where('type','customer')->get() as $image)
                                     <div class="col-md-3">
                                         <img src="{{asset("images/".$image->name)}}" alt="Rounded Image" class="rounded">
                                     </div>
                                 @endforeach
                             @else
                                 <p class="font-weight-light">No images yet</p>
                             @endif
                         </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                              <div class="form-group">
                                 <label>Admin Review</label>
                                 <textarea rows="4" cols="80" name="admin_review" id="admin_review" class="form-control-plaintext" placeholder="Here can be your traveler admin review" readonly>{{$offer->admin_review}}</textarea>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <hr>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@include('partials.sidebar-footer')
@endsection
