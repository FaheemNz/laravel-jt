@extends('layouts.main')
@section('content')
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-primary btn-round pull-right text-white " href="javascript:void(0)"
                           id="createNewUser">Add User</a>
                        <h4 class="card-title">Users</h4>
                        <div class="col-12 mt-2">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Avatar</th>
                                        <th>Name</th>
                                        <th>Email/Contact</th>
                                        <th>Currency</th>
                                        <th>Country</th>
                                        <th>Rating</th>
                                        <th>Ban</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $single_user)
                                        <tr>
                                            <th>{{$single_user->id}}</th>
                                            <td>
                                                <img src="{{$single_user->avatar}}" style="width: 50px; height: 50px; object-fit: cover" class="rounded-circle img-fluid" alt="Avatar">
                                            </td>
                                            <td>{{$single_user->first_name . " " . $single_user->last_name}}</td>
                                            <td>{{$single_user->email . " | " . $single_user->phone_no}}</td>
                                            <td>{{$single_user->currency->symbol ?? ""}} {{$single_user->currency->name ?? "-"}}</td>
                                            <td>{{$single_user->country}}</td>
                                            <td>{{$single_user->rating}}</td>
                                            <td>{!! $single_user->is_disabled? "<span class='badge badge-pill badge-danger'>Yes</span>" : "<span class='badge badge-pill badge-success'>No</span>" !!}</td>
                                            <td class="text-right">
                                                <a href="{{route("users.show", $single_user)}}" class="btn btn-info"><i class="now-ui-icons gestures_tap-01 text-dark font-weight-bold"></i></a>
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
                    <!-- end content-->
                    </div>
                    <!--  end card  -->
                </div>
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
                    @include('user.edit-form', ["user" => null])
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
        $('body').on('click', '.user-image', function () {
            $('#modal-image').attr('src', $(this).attr('src'));
            $('#imageModel').modal('toggle');
        });

        document.getElementById("createNewUser").addEventListener("click", function () {
            $('#saveBtn').text("Save");
            $('#saveBtn').val("create-user");
            $('#user_id').val('');
            $('#userForm').trigger("reset");
            $('#modelHeading').html("Create New User");
            new PerfectScrollbar("#ajaxModel", {
                wheelPropagation: true
            });
            $('#ajaxModel').modal('show');
        });
    </script>
@endsection

