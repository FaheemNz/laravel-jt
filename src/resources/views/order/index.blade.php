@extends('layouts.main')
@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <a class="btn btn-primary btn-round pull-right text-white " href="javascript:void(0)"
            id="btnCreateNewOrder">Add Order</a>
          <h4 class="card-title">Orders</h4>
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
                        <th>Traveller</th>
                        <th>Order Name</th>
                        <th>Order Route</th>
                        <th>Currency</th>
                        <th>Price & Quantity</th>
                        <th>Reward</th>
                        <th>Status</th>
                        <th>Profit</th>
                        <th>Needed By</th>
                        <th>Created On</th>
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>
                                <div class="d-flex align-items-center flex-column text-center">
                                    <img src="{{$order->user->avatar}}" style="width: 50px; height: 50px; object-fit: cover" class="rounded-circle img-fluid mb-3" alt="Avatar">
                                    <p>{{$order->user->first_name . " " . $order->user->last_name}}</p>
                                </div>
                            </td>
                            <td>
                                @if($order->traveller)
                                    <div class="d-flex align-items-center flex-column text-center">
                                        <img src="{{$order->traveller->avatar}}" style="width: 50px; height: 50px; object-fit: cover"  class="rounded-circle img-fluid mb-3" alt="Avatar">
                                        <span>{{$order->traveller->first_name . " " . $order->traveller->last_name}}</span>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{route('orders.show',$order)}}" target="_blank">{{$order->name}}</a>
                                <br>
                                <small><a href="{{$order->url}}" target="_blank">Item Link</a></small>
                            </td>
                            <td>
                                From: <small>{{$order->getCompleteSourceAddressAttribute()}}</small>
                                <br>
                                To: <small>{{$order->getCompleteDestinationAddressAttribute()}}</small>
                            </td>
                            <td>
                                {{$order->currency->name}}
                            </td>
                            <td>
                                {{$order->currency->symbol}} {{number_format($order->price)}}  <small>x{{$order->quantity}}</small>
                            </td>
                            <td>
                                {{$order->currency->symbol}} {{number_format($order->reward)}}
                            </td>
                            <td>
                                <span class="badge badge-pill badge-warning">{{$order->status}}</span>
                            </td>
                            <td width="10%">
                                $ {{number_format($order->profit,2)}}
                            </td>
                            <td>
                                {{\Carbon\Carbon::parse($order->needed_by)->format("M d, Y")}}
                            </td>
                            <td>
                                {{$order->created_at->format("M d, Y")}}
                            </td>
                            <td class="text-right">
                                <div class="d-flex flex-column justify-content-between">
                                    <a href="{{route("orders.show", $order)}}" class="btn btn-info"><i class="now-ui-icons gestures_tap-01 text-dark font-weight-bold"></i></a>
                                    <a href="#" class="btn btn-warning mt-2 mb-2"><i class="now-ui-icons design-2_ruler-pencil text-dark font-weight-bold"></i></a>
                                    <a href="#" class="btn btn-danger"><i class="now-ui-icons ui-1_simple-remove text-light font-weight-bold"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="20">
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
@include('partials.sidebar-footer')
@endsection
@section('modals')
    <div class="modal fade" id="createNewOrderModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <div class="row">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <h5 class="modal-title" id="modelHeading">Add New Order</h5>
                    </div>
                </div>
                <div class="modal-body border-bottom border-top">
                    <form id="frmAddCity" action="{{route('orders.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="row mb-3">
                                <div class="col">
                                    @include("shared.user-select")
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="name" class="control-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Order Title" maxlength="50">
                                </div>
                                <div class="col">
                                    <label for="weight" class="control-label">Item Weight</label>
                                    <select name="weight" id="" class="selectpicker form-control" data-style="btn btn-outline-primary btn-round btn-block" title="Select Weight">
                                        @foreach(\App\Utills\Constants\OrderWeight::ALL as $id=>$value)
                                            <option value="{{$id}}">{{$value}}</option>
                                        @endforeach
                                    </select>
{{--                                    <input type="number" class="form-control" id="weight" name="weight" placeholder="Enter Item Weight" maxlength="50">--}}
                                </div>
                                <div class="col">
                                    <label for="url" class="control-label">Item Complete URL</label>
                                    <input type="url" class="form-control" id="url" name="url" placeholder="Enter Item URL">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="price" class="control-label">Price</label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" maxlength="50">
                                </div>
                                <div class="col">
                                    <label for="quantity" class="control-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity" max="100">
                                </div>
                                <div class="col">
                                    <label for="reward" class="control-label">Reward</label>
                                    <input type="number" class="form-control" id="reward" name="reward" placeholder="Enter Reward Price" maxlength="50">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    @include("shared.from-city-select")
                                </div>
                                <div class="col">
                                    @include("shared.destination-city-select")
                                </div>
                                <div class="col">
                                    @include("shared.category-select")
                                </div>
                                <div class="col">
                                    @include("shared.currency-select")
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="needed_by" class="control-label">Needed By</label>
                                    <input type="date" class="form-control" name="needed_by" id="needed_by">
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="with_box" type="checkbox" value=true>
                                            <span class="form-check-sign"></span>
                                            Need WithBox?
                                        </label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="is_doorstep_delivery" type="checkbox" value=true>
                                            <span class="form-check-sign"></span>
                                            Doorstep Delivery?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="description" class="control-label">Description</label>
                                    <textarea name="description" id="description"  class="form-control" cols="30" rows="10" placeholder="Description Here..."></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer pt-3 d-flex align-items-center justify-content-end">
                    <div class="float-right">
                        <button type="reset" class="btn btn-danger" form="frmAddCity" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="frmAddCity">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img id="modal-image" src="" width="100%">
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

$('#btnCreateNewOrder').on("click", function(){
    $("#createNewOrderModal").modal("show");
})
</script>
@endsection
