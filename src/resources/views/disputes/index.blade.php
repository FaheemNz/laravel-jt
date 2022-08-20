@extends('layouts.main')
@section('content')
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Disputes</h4>
                        <div class="col-12 mt-2">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Description</th>
                                    <th>Reason</th>
                                    <th>Dispute Info</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($disputes as $dispute)
                                    <tr>
                                        <td>{{$dispute->id}}</td>
                                        <td>
                                            <div class="d-flex align-items-center flex-column text-center">
                                                <img src="{{$dispute->user->avatar}}" style="width: 50px; height: 50px; object-fit: cover" class="rounded-circle img-fluid mb-3" alt="Avatar">
                                                <p>{{$dispute->user->first_name . " " . $dispute->user->last_name}}</p>
                                            </div>
                                        </td>
                                        <td>{{$dispute->description}}</td>
                                        <td>{{$dispute->reason->description}}</td>
                                        <td>

                                            <span class="badge badge-pill badge-warning">
                                                {{\App\Utills\Constants\ReasonType::ALL[$dispute->reason->type]}}
                                            </span>
                                            @if($dispute->order)
                                                <a href="{{route("orders.show", $dispute->order_id)}}" target="_blank">
                                                    {{$dispute->order->name}}
                                                </a>
                                            @endif
                                            @if($dispute->trip)
                                                <a href="{{route("trips.show", $dispute->trip_id)}}" target="_blank">
                                                    {{$dispute->trip->completeSourceAddress}}
                                                </a>
                                            @endif
                                            @if($dispute->offer)
                                                <a href="{{route("offers.show", $dispute->offer_id)}}" target="_blank">
                                                    {{$dispute->offer->order->name}}  ({{$dispute->offer->order->currency->symbol}} {{$dispute->offer->reward}})
                                                </a>
                                            @endif
{{--                                            @if($dispute->counterOffer)--}}
{{--                                                <a href="{{route("offers.show", $dispute->offer_id)}}" target="_blank">--}}
{{--                                                    {{$dispute->offer->order->name}}  ({{$dispute->offer->reward}})--}}
{{--                                                </a>--}}
{{--                                            @else--}}
{{--                                                ---}}
{{--                                            @endif--}}
                                        </td>
                                        <td>{{\App\Utills\Constants\DisputeStatus::ALL[$dispute->status]}}</td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-success"><i class="now-ui-icons design-2_ruler-pencil text-light font-weight-bold"></i></a>
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
                    <!-- end content-->
                    </div>
                    <!--  end card  -->
                </div>
            </div>
        </div>
        @include('partials.sidebar-footer')
@endsection
@section('modals')
            <div class="modal fade" id="ajaxModel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
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
