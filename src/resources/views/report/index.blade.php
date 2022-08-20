@extends('layouts.main')
@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          {{-- <a class="btn btn-primary btn-round pull-right text-white " href="javascript:void(0)"
            id="createNewReport">Add Report</a> --}}
          <h4 class="card-title">Reports</h4>
          <div class="col-12 mt-2">
          </div>
        </div>
        <div class="card-body">
          <div class="toolbar">
            <!--        Here you can write extra buttons/actions for the toolbar              -->
          </div>
          <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
              <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><strong>Status :</strong></label>
                            <select id='status' class="form-control" style="width: 200px">
                                <option value="" disabled>--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Deleted</option>
                            </select>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                            <label><strong>Is Reviewed :</strong></label>
                            <select id='is_reviewed_data_table' class="form-control" style="width: 200px">
                                <option value="" disabled>--Is Reviewed--</option>
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                            <label><strong>Is Resolved :</strong></label>
                            <select id='is_resolved_data_table' class="form-control" style="width: 200px">
                                <option value="" disabled>--Is Resolved--</option>
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                      </div>
                  </div>
                <table id="datatable" class="table table-responsive table-striped table-bordered dataTable dtr-inline data-table"
                  cellspacing="0" width="100%" role="grid" aria-describedby="datatable_info" style="width: 100%;">
                  <thead>
                    <tr role="row">
                      <th></th>
                      <th>Id</th>
                      <th>Reported By</th>
                      <th>Entity</th>
                      <th>Reason</th>
                      <th>Description</th>
                      <th>Is Reviewed</th>
                      <th>Is Resolved</th>
                      <th>Admin Review</th>
                      <th>Created At</th>
                      <th width="200px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- end content-->
      </div>
      <!--  end card  -->
    </div>
  </div>
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
          <form id="reportForm" name="reportForm" class="form-horizontal" enctype='multipart/form-data'>
            <input type="hidden" name="report_id" id="report_id">
            <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Reported By</label>
              <div class="col-sm-12">
                <input type="text" class="form-control" id="name" name="" placeholder="Enter Name" value=""
                  maxlength="50" disabled>
              </div>
            </div>
            {{-- <div>
              <div class="dropdown bootstrap-select">
                <select class="selectpicker" name="gender" id="gender"
                  data-style="btn btn-primary btn-round btn-block" title="Select Gender"
                  value="">
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Others</option>
                </select>
              </div>
            </div> --}}
            {{-- <div class="form-group">
              <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" name="status" id="status" type="checkbox" value="on">
                    Is Active
                    <span class="form-check-sign">
                        <span class="check"></span>
                    </span>
                </label>
              </div>
            </div> --}}
            <div class="form-group">
                <label for="admin_review" class="col-sm-2 control-label">Admin Review</label>
                <div class="col-sm-12">
                    <textarea class="form-control" rows="5" id="admin_review" name="admin_review"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label><strong>Is Reviewed :</strong></label>
                <select id="is_reviewed" name="is_reviewed" class="form-control" style="width: 200px" aria-valuemax="">
                    <option value="" disabled>--Is Reviewed--</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label><strong>Is Resolved :</strong></label>
                <select id="is_resolved" name="is_resolved" class="form-control" style="width: 200px" aria-valuemax="">
                    <option value="" disabled>--Is Resolved--</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
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
</div>
@include('partials.sidebar-footer')
@section('script')
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
  $(function () {
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
     });

     var table = $('.data-table').DataTable({
        responsive:true,
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('reports.index') }}",
          data: function (d) {
                d.status = $('#status').val();
                d.is_reviewed = $('#is_reviewed_data_table').val();
                d.is_resolved = $('#is_resolved_data_table').val();
            }
        },
        columns: [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            {data: 'id', name: 'id'},
            {data: 'reported_by', name: 'reported_by'},
            {data: 'entity', name: 'entity'},
            {data: 'reason', name: 'reason'},
            {data: 'description', name: 'description'},
            {data: 'is_reviewed', name: 'is_reviewed'},
            {data: 'is_resolved', name: 'is_resolved'},
            {data: 'admin_review', name: 'admin_review'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            // {data: 'is_deleted', name: 'is_deleted'},
        ]
     });
     $('#status').change(function(){
        table.draw();
     });
     $('#is_reviewed_data_table').change(function(){
        table.draw();
     });
     $('#is_resolved_data_table').change(function(){
        table.draw();
     });
     $('#createNewReport').click(function () {
        $('#saveBtn').text("Save");
        $('#saveBtn').val("create-report");
        $('#report_id').val('');
        $('#reportForm').trigger("reset");
        $('#modelHeading').html("Create New Report");
        new PerfectScrollbar("#ajaxModel", {
          wheelPropagation: true
        });
        $('#ajaxModel').modal('show');
     });

     $('body').on('click', '.editReport', function () {
       $('#saveBtn').text("Update");
       let report_id = $(this).data('id');
       $.get("{{ route('reports.index') }}" +'/' + report_id +'/edit', function (data) {
           $('#modelHeading').html("Edit Report " + report_id);
           $('#saveBtn').val("edit-report");
           $('#ajaxModel').modal('show');
           $('#report_id').val(data.id);
           $('#name').val(data.created_by.first_name +' '+ data.created_by.last_name);
           $('#admin_review').val(data.admin_review);
           $('#is_reviewed').val(data.is_reviewed);
           $('#is_resolved').val(data.is_resolved);

       })
    });

     $('#saveBtn').click(function (e) {
         e.preventDefault();
         let button_text = $('#saveBtn').text();
         if(!$('#reportForm').valid())
         {
           return false;
         }
         $(this).html('Sending..');
         $.ajax({
           data:  new FormData($('#reportForm')[0]),
           url: "{{ route('reports.store') }}",
           type: 'POST',
           dataType: 'json',
           processData: false,
           contentType: false,
           success: function (data) {
               $('#saveBtn').html(button_text);
               $('#reportForm').trigger("reset");
               $('#ajaxModel').modal('hide');
               table.draw();
               demo.showNotificationSuccess('top','center',data.success);
               console.log('SUCCESS',data);
           },
           error: function (data) {
            console.log('Error',data);
               $('#saveBtn').html(button_text);
               let errors = JSON.parse(data.responseText).error;
               let result = Object.keys(errors).map(function(key) {
                 return [key, errors[key]];
               });
               let body = '<ul>';
               result.forEach(error => {
                 body+= '<li>'+error[1]+'</li>';
               });
               body+='</ul>';
               demo.showNotificationError('top','center',body);
           }
       });
     });

     $('body').on('click', '.deleteReport', function () {
         let button_text = $('#saveBtn').text();
         var report_id = $(this).data("id");
         Swal.fire({
                 title: 'Are you sure?',
                 text: "You won't be able to revert this!",
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonClass: 'btn btn-success',
                 cancelButtonClass: 'btn btn-danger',
                 confirmButtonText: 'Yes, delete it!',
                 buttonsStyling: false
             }).then((result) => {
                 if (result.value) {
                   $.ajax({
                    type: "DELETE",
                    url: "{{ route('reports.store') }}"+'/'+report_id,
                    success: function (data) {
                        $('#saveBtn').html(button_text);
                        table.draw();
                        Swal.fire({
                                title: 'Deleted!',
                                text: 'Your report has been deleted.',
                                type: 'success',
                                confirmButtonClass: 'btn btn-success',
                                buttonsStyling: false
                            });
                    },
                    error: function (data) {
                      let errors = JSON.parse(data.responseText).error;
                      let result = Object.keys(errors).map(function(key) {
                          return [key, errors[key]];
                      });
                      let body = '<ul>';
                      result.forEach(error => {
                          body += '<li>' + error[1] + '</li>';
                      });
                      body += '</ul>';
                      demo.showNotificationError('top', 'center', body);
                    }
                  });
                 }
             });
     });
   });
</script>
@endsection
@endsection
