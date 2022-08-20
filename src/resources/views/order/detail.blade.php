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
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="title"><a href="{{route("orders.show", $order->id)}}">Order #{{$order->id}}</a></h5>
                            </div>
                            <div class="progress">
                                @php
                                    $allStatuses = \App\Utills\Constants\OrderStatus::ALL;
                                    $currentStatusIndex = array_search($order->status, \App\Utills\Constants\OrderStatus::ALL) + 1;
                                    $per = round(ceil(($currentStatusIndex / count($allStatuses)) * 100));
                                @endphp
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
            <div class="row">
                @if($order->payment)
                    <div class="col-md-12">
                        <div class="card" style="background-color: #5cb85c4f">
                            <div class="card-header">
                                <h2 class="title">Accepted {{$order->payment->offer? "Offer " : "Counter Offer"}}</h2>
                            </div>
                            <div class="card-body">
                                @include('order.offer-detail', array('offer' => $order->payment->offer? $order->payment->offer : $order->payment->counterOffer->offer))
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="card jumbotron">
                            <div class="card-body">
                                <h5 class="title text-center text-muted">No Offer Selected Yet</h5>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    @include("shared.cards.user-cards", ["user" => $order->createdBy])
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-user">
                        <div class="card-body">
                            <h5 class="title">Related Images</h5>
                            <div class="row m-0">
                                <div class="col-12 mb-3">

                                    <img src="/images/{{$order->thumbnail}}" class="img-thumbnail" alt="Thumbnail">
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <h6 class="title">Order Images</h6>
                                    </div>
                                    <div class="row">
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
                            </div>
                            <div class="row m-0">
                                <div class="col-md-12">
                                    <div class="row">
                                        <h6 class="title">Purchase Receipt</h6>
                                    </div>
                                    <div class="row">
                                        @forelse ($order->imageOrder()->where('type','receipt')->get() as $orderImage)
                                            <div class="col-md-3">
                                                <img src="{{asset(\App\Utills\Constants\FilePaths::BASE_IMAGES_PATH.$orderImage->image->name)}}" alt="Rounded Image" class="rounded">
                                            </div>
                                        @empty
                                            <p class="font-weight-light">No images yet</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="col-md-12">
                                    <div class="row">
                                        <h6 class="title">Custom Duty Receipt</h6>
                                    </div>
                                    <div class="row">
                                        @forelse ($order->imageOrder()->where('type','custom_duty')->get() as $orderImage)
                                            <div class="col-md-3">
                                                <img src="{{asset(\App\Utills\Constants\FilePaths::BASE_IMAGES_PATH.$orderImage->image->name)}}" alt="Rounded Image" class="rounded">
                                            </div>
                                        @empty
                                            <p class="font-weight-light">No images yet</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Admin Review</label>
                                            <textarea rows="4" cols="80" name="admin_review" id="admin_review" class="form-control-plaintext" placeholder="Here can be your traveler admin review" readonly>{{$order->admin_review}}</textarea>
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
    @if($order->offers)
       <div class="row">
          <div class="col-md-12">
             <div class="card">
                <div class="card-header">
                   <h2 class="title">All Offers</h2>
                </div>
                <div class="card-body table-responsive">
                    <table class="customDataTable table table-striped">
                        <thead class="bg-warning text-primary">
                            <tr role="row">
                                <th>Id</th>
                                <th>Traveller</th>
                                <th>Demand Price</th>
                                <th>Demand Reward</th>
                                <th>Offer Status</th>
                                <th>Offer Expiry</th>
                                <th>Counter</th>
                                <th>Counter Status</th>
                                <th>Counter Expiry</th>
                            </tr>
                         </thead>
                         <tbody>
                            @foreach($order->offers as $offer)
                                <tr>
                                    <td>{{$offer->id}}</td>
                                    <td>{{$offer->user->first_name . " " . $offer->user->last_name}}</td>
                                    <td>{{$offer->currency->symbol}} {{number_format($offer->price)}}</td>
                                    <td>{{$offer->currency->symbol}} {{number_format($offer->reward)}}</td>
                                    <td>
                                        <span class="badge badge-pill {{in_array($offer->status, \App\Utills\Constants\OfferStatus::NEGATIVE)? "badge-danger" : "badge-warning"}} ">
                                            {{$offer->status}}
                                        </span>
                                    </td>
                                    <td>{{\Carbon\Carbon::parse($offer->expiry_date)->format("M d, Y")}}</td>
                                    @if($offer->counterOffer)
                                        <td>{{$offer->counterOffer->currency->symbol}} {{number_format($offer->counterOffer->reward)}}</td>
                                        <td>
                                            <span class="badge badge-pill {{in_array($offer->counterOffer->status, \App\Utills\Constants\OfferStatus::NEGATIVE)? "badge-danger" : "badge-warning"}} ">
                                                {{$offer->counterOffer->status}}
                                            </span>
                                        </td>
                                        <td>{{Carbon\Carbon::parse($offer->counterOffer->expiry_date)->format("M d, Y")}}</td>
                                    @else
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    @endif
                                </tr>
                            @endforeach
                         </tbody>
                      </table>
                   </div>
                </div>
             </div>
          </div>
       </div>
    @endif
@include('partials.sidebar-footer')
</div>
@endsection

@section('modals')
<div class="modal fade" id="ajaxModel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <div class="row">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
               <i class="now-ui-icons ui-1_simple-remove"></i>
               </button>
               <h5 class="modal-title" id="modelHeading"></h5>
            </div>
         </div>
         <div class="modal-body">
            <form id="orderForm" name="orderForm" class="form-horizontal" enctype='multipart/form-data'>
               <input type="hidden" name="order_id" id="order_id">
               <div class="form-group">
                  <label for="first_name" class="col-sm-3 control-label">First Name</label>
                  <div class="col-sm-12">
                     <input type="text" class="form-control-plaintext" id="first_name" name="first_name" placeholder="Enter First Name" value=""
                        maxlength="50" required>
                  </div>
               </div>
               <div class="form-group">
                  <label for="surname" class="col-sm-2 control-label">Surname</label>
                  <div class="col-sm-12">
                     <input type="text" class="form-control-plaintext" id="surname" name="surname" placeholder="Enter Surname" value=""
                        maxlength="50" required>
                  </div>
               </div>
               <div class="form-group">
                  <label for="address" class="col-sm-2 control-label">Address</label>
                  <div class="col-sm-12">
                     <textarea id="address" name="address" placeholder="Enter address" rows="4" cols="50">
                     </textarea>
                  </div>
               </div>
               <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-12">
                     <input type="text" class="form-control-plaintext" id="password" name="password" placeholder="Enter password" value=""
                        maxlength="50" required>
                  </div>
               </div>
               <div class="form-group">
                  <label for="phone" class="col-sm-2 control-label">Phone</label>
                  <div class="col-sm-12">
                     <input type="text" class="form-control-plaintext" id="phone" name="phone" placeholder="Enter phone" value=""
                        maxlength="50" required>
                  </div>
               </div>
               <div class="form-group">
                  <label for="dob" class="col-sm-2 control-label">DOB</label>
                  <div class="col-sm-12">
                     <input type="date" class="form-control-plaintext" id="dob" name="dob" placeholder="Enter dob" value=""
                        maxlength="50" required>
                  </div>
               </div>
               <div class="form-group">
                  <label for="email" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-12">
                     <input type="text" class="form-control-plaintext" id="email" name="email" placeholder="Enter email" value=""
                        maxlength="50" required>
                  </div>
               </div>
               <div>
                  <div class="dropdown bootstrap-select">
                     <select class="selectpicker" name="gender" id="gender"
                        data-style="btn btn-primary btn-round btn-block" title="Select Gender"
                        value="">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Others</option>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <div class="form-check">
                     <label class="form-check-label">
                     <input class="form-check-input" name="status" id="status" type="checkbox" value="on">
                     Is Active
                     <span class="form-check-sign">
                     <span class="check"></span>
                     </span>
                     </label>
                  </div>
               </div>
               <div class="col-sm-offset-2 col-sm-12">
                  <button type="submit" class="btn btn-primary btn-round float-right" id="saveBtn" value="create">Save
                  changes
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
<script>
   $('body').on('click', '.order-image', function () {
     $('#modal-image').attr('src',$(this).attr('src'));
     $('#imageModel').modal('toggle');
   });

</script>
@endsection

