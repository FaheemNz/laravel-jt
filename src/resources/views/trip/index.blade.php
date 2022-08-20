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
                            Add Trip
                        </a>
                        <h4 class="card-title">Trips</h4>
                        <div class="col-12 mt-2">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                <tr>
                                    <td>ID</td>
                                    <th>Traveller</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Arrival Date</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($trips as $trip)
                                    <tr>
                                        <td>{{$trip->id}}</td>
                                        <td>
                                            <div class="d-flex align-items-center flex-column text-center">
                                                <img src="{{$trip->user->avatar}}" style="width: 50px; height: 50px; object-fit: cover" class="rounded-circle img-fluid mb-3" alt="Avatar">
                                                <p>{{$trip->user->first_name . " " . $trip->user->last_name}}</p>
                                            </div></td>
                                        <td>{{$trip->sourceCity->name . ", ". $trip->sourceCity->state->country->name}}</td>
                                        <td>{{$trip->destinationCity->name . ", ". $trip->destinationCity->state->country->name}}</td>
                                        <td>{{\Carbon\Carbon::parse($trip->arrival_date)->format("M d, Y")}}</td>
                                        <td>{{$trip->created_at->format("M d, Y")}}</td>
                                        <td>{!! $trip->status == "inactive"? "<span class='badge badge-pill badge-danger'>In Active</span>" : "<span class='badge badge-pill badge-success'>Active</span>" !!}</td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-warning"><i class="now-ui-icons design-2_ruler-pencil text-dark font-weight-bold"></i></a>
                                            <a href="#" class="btn btn-danger"><i class="now-ui-icons ui-1_simple-remove text-light font-weight-bold"></i></a>
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
    </div>
    @include('partials.sidebar-footer')
@endsection
@section('modals')
    <div class="modal fade" id="createNewTripModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <div class="row">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <h5 class="modal-title" id="modelHeading">Add New Reason</h5>
                    </div>
                </div>
                <div class="modal-body border-bottom border-top">
                    <form id="frmAddReason" action="{{route('trips.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    @include("shared.user-select")
                                </div>
                                <div class="col">
                                    @include("shared.from-city-select")
                                </div>
                                <div class="col">
                                    @include("shared.destination-city-select")
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="arrival_date">Arrival Date</label>
                                    <input type="date" name="arrival_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer pt-3 d-flex align-items-center justify-content-end">
                    <div class="float-right">
                        <button type="reset" class="btn btn-danger" form="frmAddReason" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="frmAddReason">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#btnCreateNewTrip').on("click", function(){
            $("#createNewTripModal").modal("show");
        })
    </script>
@endsection
