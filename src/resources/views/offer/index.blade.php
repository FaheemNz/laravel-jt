@extends('layouts.main')
@section('content')
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a  class="btn btn-primary btn-round pull-right text-white "
                            href="javascript:void(0);"
                            id="btnCreateNewOffer"
                        >
                            Add Offer
                        </a>

                        <h4 class="card-title">Offers</h4>
                        <div class="col-12 mt-2">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Traveller</th>
                                    <th>Offer Price</th>
                                    <th>Offer Reward</th>
                                    <th>Status</th>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Counter Offer Reward</th>
                                    <th class="text-right">Action</th>
                                </tr>

                                </thead>
                                <tbody>
                                @forelse($offers as $offer)
                                    <tr>
                                        <td>{{$offer->id}}</td>

                                        <td>
                                            <div class="d-flex align-items-center flex-column text-center">
                                                <img src="{{$offer->user->avatar}}" style="width: 50px; height: 50px; object-fit: cover" class="rounded-circle img-fluid mb-3" alt="Avatar">
                                                <p>{{$offer->user->first_name . " " . $offer->user->last_name}}</p>
                                            </div>
                                        </td>
                                        <td>{{$offer->currency->symbol}} {{number_format($offer->price)}}</td>
                                        <td>{{$offer->currency->symbol}} {{number_format($offer->reward)}}</td>
                                        <td><span class="badge badge-pill badge-warning">{{$offer->status}}</span></td>
                                        <td>
                                            <a href="{{route('orders.show', $offer->order_id)}}" target="_blank">
                                                {{$offer->order->name}}
                                            </a>
                                        </td>
                                        <td>
                                            @if($offer->counterOffer)
                                                {{$offer->counterOffer->user->first_name}} {{$offer->counterOffer->user->last_name}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($offer->counterOffer)
                                                {{$offer->counterOffer->currency->symbol}} {{number_format($offer->counterOffer->reward)}}
                                            @else
                                                -
                                            @endif
                                        </td>
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
    @include('partials.sidebar-footer')
@endsection
@section("modal")
    <div class="modal fade" id="createNewOfferModal" aria-hidden="true">
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
@endsection
@section('script')
    <script>
        $('#btnCreateNewOffer').on("click", function(){
            $("#createNewOfferModal").modal("show");
        })
    </script>
@endsection




{{--@extends('layouts.main')--}}
{{--@section('content')--}}
{{--<div class="panel-header panel-header-sm"></div>--}}
{{--<div class="content">--}}
{{--  <div class="row">--}}
{{--    <div class="col-md-12">--}}
{{--      <div class="card">--}}
{{--        <div class="card-header">--}}
{{--          --}}{{-- <a class="btn btn-primary btn-round pull-right text-white " href="javascript:void(0)"--}}
{{--            id="createNewOffer">Add Offer</a> --}}
{{--          <h4 class="card-title">Offers</h4>--}}
{{--          <div class="col-12 mt-2">--}}
{{--          </div>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--          <div class="toolbar">--}}
{{--            <!--        Here you can write extra buttons/actions for the toolbar              -->--}}
{{--          </div>--}}
{{--          <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4">--}}
{{--            <div class="row">--}}
{{--              <div class="col-sm-12">--}}
{{--                <div class="row">--}}
{{--                    <div class="col">--}}
{{--                        <div class="form-group">--}}
{{--                            <label><strong>Status :</strong></label>--}}
{{--                            <select id='status_data_table' class="form-control" style="width: 200px">--}}
{{--                                <option value="">--Select Status--</option>--}}
{{--                                <option value="1" selected>Active</option>--}}
{{--                                <option value="0">Deleted</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col">--}}
{{--                        <div class="form-group">--}}
{{--                            <label><strong>Order Id :</strong></label>--}}
{{--                            <input id="order_id_data_table" type="number" class="form-control">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col">--}}
{{--                        <div class="form-group">--}}
{{--                            <label><strong>Is Disabled :</strong></label>--}}
{{--                            <select id='is_disabled_data_table' class="form-control" style="width: 200px">--}}
{{--                                <option value="" disabled>--Is Disabled--</option>--}}
{{--                                <option value="1">Yes</option>--}}
{{--                                <option value="0" selected>No</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <table id="datatable" class="table table-responsive table-striped table-boffered dataTable dtr-inline data-table"--}}
{{--                  cellspacing="0" width="100%" role="grid" aria-describedby="datatable_info" style="width: 100%;">--}}
{{--                  <thead>--}}
{{--                    <tr role="row">--}}
{{--                      <th></th>--}}
{{--                      <th>Id</th>--}}
{{--                      <th>Status</th>--}}
{{--                      <th>Price</th>--}}
{{--                      <th>Reward</th>--}}
{{--                      <th>Currency</th>--}}
{{--                      <th>Expiry Date</th>--}}
{{--                      <th>From City</th>--}}
{{--                      <th>Destination City</th>--}}
{{--                      <th>Arrival Date</th>--}}
{{--                      <th>Order Id</th>--}}
{{--                      <th>Created By</th>--}}
{{--                      <th>Created At</th>--}}
{{--                      <th width="200px">Action</th>--}}
{{--                      <th>Is Disabled</th>--}}
{{--                      <th width="500px">Admin Reviews</th>--}}
{{--                    </tr>--}}
{{--                  </thead>--}}
{{--                  <tbody>--}}
{{--                  </tbody>--}}
{{--                </table>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--        <!-- end content-->--}}
{{--      </div>--}}
{{--      <!--  end card  -->--}}
{{--    </div>--}}
{{--  </div>--}}
{{--  @section('modals')--}}
{{--  <div class="modal fade" id="ajaxModel" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-lg">--}}
{{--      <div class="modal-content">--}}
{{--        <div class="modal-header">--}}
{{--          <div class="row">--}}
{{--            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">--}}
{{--              <i class="now-ui-icons ui-1_simple-remove"></i>--}}
{{--            </button>--}}
{{--            <h5 class="modal-title" id="modelHeading"></h5>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--        <div class="modal-body">--}}
{{--            @include('offer.edit-form')--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--  </div>--}}

{{--  <div class="modal fade" id="imageModel" aria-hidden="true">--}}
{{--    <div class="modal-dialog">--}}
{{--      <div class="modal-content">--}}
{{--        <div class="modal-body p-0">--}}
{{--          <img id="modal-image" src="" width="100%">--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--  @endsection--}}
{{--</div>--}}
{{--@include('partials.sidebar-footer')--}}
{{--@section('script')--}}
{{--<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>--}}
{{--<script type="text/javascript">--}}
{{--  $(function () {--}}
{{--       $.ajaxSetup({--}}
{{--           headers: {--}}
{{--               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--           }--}}
{{--     });--}}

{{--     var table = $('.data-table').DataTable({--}}
{{--        responsive:true,--}}
{{--        processing: true,--}}
{{--        serverSide: true,--}}
{{--        ajax: {--}}
{{--          url: "{{ route('offers.index') }}",--}}
{{--          data: function (d) {--}}
{{--                d.status = $('#status_data_table').val();--}}
{{--                d.order_id = $('#order_id_data_table').val();--}}
{{--                d.is_disabled = $('#is_disabled_data_table').val();--}}
{{--            }--}}
{{--        },--}}
{{--        columns: [--}}
{{--            {--}}
{{--                "className":      'details-control',--}}
{{--                "orderable":      false,--}}
{{--                "data":           null,--}}
{{--                "defaultContent": ''--}}
{{--            },--}}
{{--            {data: 'id', name: 'id'},--}}
{{--            {data: 'status', name: 'status'},--}}
{{--            {data: 'price', name: 'price'},--}}
{{--            {data: 'reward', name: 'reward'},--}}
{{--            {data: 'currency', name: 'currency'},--}}
{{--            {data: 'expiry_date', name: 'expiry_date'},--}}
{{--            {data: 'source_city', name: 'source_city'},--}}
{{--            {data: 'destination_city', name: 'destination_city'},--}}
{{--            {data: 'arrival_date', name: 'arrival_date'},--}}
{{--            {data: 'order_id', name: 'order_id'},--}}
{{--            {data: 'offer_by', name: 'offer_by'},--}}
{{--            {data: 'created_at', name: 'created_at'},--}}
{{--            {data: 'action', name: 'action', offerable: false, searchable: false},--}}
{{--            {data: 'is_disabled', name: 'is_disabled'},--}}
{{--            {data: 'admin_review', name: 'admin_review'},--}}
{{--            // {data: 'is_deleted', name: 'is_deleted'},--}}
{{--        ]--}}
{{--     });--}}
{{--     $('#status_data_table').change(function(){--}}
{{--        table.draw();--}}
{{--     });--}}
{{--     $('#order_id_data_table').change(function(){--}}
{{--        table.draw();--}}
{{--     });--}}
{{--     $('#is_disabled_data_table').change(function(){--}}
{{--        table.draw();--}}
{{--     });--}}

{{--     $('#createNewOffer').click(function () {--}}
{{--        $('#saveBtn').text("Save");--}}
{{--        $('#saveBtn').val("create-offer");--}}
{{--        $('#offer_id').val('');--}}
{{--        $('#offerForm').trigger("reset");--}}
{{--        $('#modelHeading').html("Create New Offer");--}}
{{--        new PerfectScrollbar("#ajaxModel", {--}}
{{--          wheelPropagation: true--}}
{{--        });--}}
{{--        $('#ajaxModel').modal('show');--}}
{{--     });--}}

{{--     $('body').on('click', '.editOffer', function () {--}}
{{--       $('#saveBtn').text("Update");--}}
{{--       let offer_id = $(this).data('id');--}}
{{--       $.get("{{ route('offers.index') }}" +'/' + offer_id +'/edit', function (data) {--}}
{{--           $('#modelHeading').html("Edit Offer " + offer_id);--}}
{{--           $('#saveBtn').val("edit-offer");--}}
{{--           $('#ajaxModel').modal('show');--}}
{{--           $('#offer_id').val(data.id);--}}
{{--           $('#description').val(data.description);--}}
{{--           $('#created_by').val(data.created_by.first_name +' '+ data.created_by.last_name);--}}
{{--           $('#price').val(data.price);--}}
{{--           $('#reward').val(data.reward);--}}
{{--           $('#service_charges').val(data.service_charges);--}}
{{--           $('.selectpicker#status').selectpicker('val', data.status);--}}
{{--           $('.selectpicker#currency').selectpicker('val', data.currency_id);--}}
{{--           $('#expiry_date').val(data.expiry_date);--}}
{{--           (data.is_disabled) ? $('#is_disabled').prop('checked',true) : $('#is_disabled').prop('checked',false);--}}
{{--           $('#admin_review').val(data.admin_review);--}}
{{--       });--}}
{{--    });--}}

{{--    $('body').on('click', '.viewOffers', function () {--}}
{{--        let offer_id = $(this).data('id');--}}
{{--        $('#modelHeadingOffer').html('Offer '+offer_id+' Offers');--}}
{{--        var url = '';--}}
{{--        url = url.replace(':id', offer_id);--}}
{{--        $('#offerTableBody').empty();--}}
{{--        $.get(url, function (data) {--}}
{{--          let body= '';--}}
{{--          data.forEach(offerOffer => {--}}
{{--            console.log('offerOffer.offer',offerOffer.offer);--}}
{{--            if(offerOffer.offer) {--}}

{{--                body += '<tr>';--}}
{{--                body+= '<td>'+offerOffer.ID+'</td>';--}}
{{--                body+= '<td>'+offerOffer.offer.NAME+'</td>';--}}
{{--                body+= '<td>'+offerOffer.offer.DESCRIPTION+'</td>';--}}
{{--                body+= '<td>'+offerOffer.offer.CODE+'</td>';--}}
{{--                body+= '<td><img class="offer-image" src="'+offerOffer.offer.IMAGE_URL+'" alt="Offer Images" width="100" height="100"></td>';--}}
{{--                if(offerOffer.STATUS == '1') {--}}
{{--                  body+='<td><span class="badge badge-success">Active</span></td>'--}}
{{--                } else {--}}
{{--                  body+= '<td><span class="badge badge-danger">Redeemed</span></td>';--}}
{{--                }--}}

{{--                if(offerOffer.REDEEM_DATE == null) {--}}
{{--                  body+= '<td></td>';--}}
{{--                } else {--}}
{{--                    body+= '<td>'+offerOffer.REDEEM_DATE+'</td>';--}}
{{--                }--}}

{{--                body+='</tr>';--}}

{{--            }--}}
{{--          });--}}
{{--          $('#offerTableBody').append(body);--}}
{{--          $('#offerModel').modal('show');--}}
{{--        });--}}
{{--    });--}}

{{--     $('#saveBtn').click(function (e) {--}}
{{--         e.preventDefault();--}}
{{--         let button_text = $('#saveBtn').text();--}}
{{--         if(!$('#offerForm').valid())--}}
{{--         {--}}
{{--           return false;--}}
{{--         }--}}
{{--         $(this).html('Sending..');--}}
{{--         $.ajax({--}}
{{--           data:  new FormData($('#offerForm')[0]),--}}
{{--          //  url: "{{ route('offers.store') }}",--}}
{{--           type: 'POST',--}}
{{--           dataType: 'json',--}}
{{--           processData: false,--}}
{{--           contentType: false,--}}
{{--           success: function (data) {--}}
{{--               $('#saveBtn').html(button_text);--}}
{{--               $('#offerForm').trigger("reset");--}}
{{--               $('#ajaxModel').modal('hide');--}}
{{--               table.draw();--}}
{{--               demo.showNotificationSuccess('top','center',data.success);--}}
{{--               console.log('SUCCESS',data);--}}
{{--           },--}}
{{--           error: function (data) {--}}
{{--            console.log('Error',data);--}}
{{--               $('#saveBtn').html(button_text);--}}
{{--               let errors = JSON.parse(data.responseText).error;--}}
{{--               let result = Object.keys(errors).map(function(key) {--}}
{{--                 return [key, errors[key]];--}}
{{--               });--}}
{{--               let body = '<ul>';--}}
{{--               result.forEach(error => {--}}
{{--                 body+= '<li>'+error[1]+'</li>';--}}
{{--               });--}}
{{--               body+='</ul>';--}}
{{--               demo.showNotificationError('top','center',body);--}}
{{--           }--}}
{{--       });--}}
{{--     });--}}

{{--     $('body').on('click', '.deleteOffer', function () {--}}
{{--         let button_text = $('#saveBtn').text();--}}
{{--         var offer_id = $(this).data("id");--}}
{{--         Swal.fire({--}}
{{--                 title: 'Are you sure?',--}}
{{--                 text: "You won't be able to revert this!",--}}
{{--                 type: 'warning',--}}
{{--                 showCancelButton: true,--}}
{{--                 confirmButtonClass: 'btn btn-success',--}}
{{--                 cancelButtonClass: 'btn btn-danger',--}}
{{--                 confirmButtonText: 'Yes, delete it!',--}}
{{--                 buttonsStyling: false--}}
{{--             }).then((result) => {--}}
{{--                 if (result.value) {--}}
{{--                   $.ajax({--}}
{{--                    type: "DELETE",--}}
{{--                    url: "{{ route('offers.store') }}"+'/'+offer_id,--}}
{{--                    success: function (data) {--}}
{{--                        $('#saveBtn').html(button_text);--}}
{{--                        table.draw();--}}
{{--                        Swal.fire({--}}
{{--                                title: 'Deleted!',--}}
{{--                                text: 'Your offer has been deleted.',--}}
{{--                                type: 'success',--}}
{{--                                confirmButtonClass: 'btn btn-success',--}}
{{--                                buttonsStyling: false--}}
{{--                            });--}}
{{--                    },--}}
{{--                    error: function (data) {--}}
{{--                      let errors = JSON.parse(data.responseText).error;--}}
{{--                      let result = Object.keys(errors).map(function(key) {--}}
{{--                          return [key, errors[key]];--}}
{{--                      });--}}
{{--                      let body = '<ul>';--}}
{{--                      result.forEach(error => {--}}
{{--                          body += '<li>' + error[1] + '</li>';--}}
{{--                      });--}}
{{--                      body += '</ul>';--}}
{{--                      demo.showNotificationError('top', 'center', body);--}}
{{--                    }--}}
{{--                  });--}}
{{--                 }--}}
{{--             });--}}
{{--     });--}}

{{--          $('body').on('click', '.disableOffer', function () {--}}
{{--         let button_text = $('#saveBtn').text();--}}
{{--         var offer_id = $(this).data("id");--}}
{{--         Swal.fire({--}}
{{--                 title: 'Are you sure?',--}}
{{--                 text: "You won't be able to revert this!",--}}
{{--                 type: 'warning',--}}
{{--                 showCancelButton: true,--}}
{{--                 confirmButtonClass: 'btn btn-success',--}}
{{--                 cancelButtonClass: 'btn btn-danger',--}}
{{--                 confirmButtonText: 'Yes',--}}
{{--                 buttonsStyling: false--}}
{{--             }).then((result) => {--}}
{{--                 if (result.value) {--}}
{{--                   $.ajax({--}}
{{--                    type: "GET",--}}
{{--                    url: "{{ url('/offers') }}"+'/'+offer_id+'/disable',--}}
{{--                    success: function (data) {--}}
{{--                        $('#saveBtn').html(button_text);--}}
{{--                        table.draw();--}}
{{--                        Swal.fire({--}}
{{--                                title: 'Banned!',--}}
{{--                                text: 'Offer has been disabled.',--}}
{{--                                type: 'success',--}}
{{--                                confirmButtonClass: 'btn btn-success',--}}
{{--                                buttonsStyling: false--}}
{{--                            });--}}
{{--                    },--}}
{{--                    error: function (data) {--}}
{{--                      let errors = JSON.parse(data.responseText).error;--}}
{{--                      let result = Object.keys(errors).map(function(key) {--}}
{{--                          return [key, errors[key]];--}}
{{--                      });--}}
{{--                      let body = '<ul>';--}}
{{--                      result.forEach(error => {--}}
{{--                          body += '<li>' + error[1] + '</li>';--}}
{{--                      });--}}
{{--                      body += '</ul>';--}}
{{--                      demo.showNotificationError('top', 'center', body);--}}
{{--                    }--}}
{{--                  });--}}
{{--                 }--}}
{{--             });--}}
{{--     });--}}

{{--   });--}}
{{--</script>--}}
{{--<script>--}}
{{--$('body').on('click', '.offer-image', function () {--}}
{{--  $('#modal-image').attr('src',$(this).attr('src'));--}}
{{--  $('#imageModel').modal('toggle');--}}
{{--});--}}

{{--</script>--}}
{{--@endsection--}}
{{--@endsection--}}
