@extends('layouts.main')
@section('banner')
    <div class="panel-header panel-header-sm"></div>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-primary btn-round pull-right text-white" href="{{route('notifications.markAllRead')}}">Mark All Read</a>
                        <a class="btn btn-danger btn-round pull-right text-white" href="{{route('notifications.deleteAll')}}">Delete All</a>
                        <h5 class="title">All Notification</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse(auth()->user()->notifications as $notification)
                                    <tr>
                                        <td>
                                            {{$notification->created_at->diffForHumans()}}
                                        </td>
                                        <td>
                                            <span class="badge badge-pill badge-warning">{{$notification->data['type'] ?? "No Type"}}</span>
                                        </td>
                                        <td>
                                            {{$notification->data['title'] ?? "No Title"}}
                                        </td>
                                        <td>
                                            {{$notification->data['description'] ?? "No Description"}}
                                        </td>
                                        <td class="text-right d-flex align-items-center justify-content-end">
                                            @if(!$notification->read_at)
                                                <a href="{{route('notification.markRead', $notification)}}" title="Mark As Read" class="btn btn-success mr-2"><i class="now-ui-icons now-ui-icons ui-1_check text-light font-weight-bold"></i></a>
                                            @endif
                                            <a href="{{route('notification.delete', $notification)}}" title="Delete" class="btn btn-danger"><i class="now-ui-icons ui-1_simple-remove text-light font-weight-bold"></i></a>
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
@section('modals')
    <div class="modal fade" id="createNewCategoryModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <div class="row">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <h5 class="modal-title" id="modelHeading">Add New Category</h5>
                    </div>
                </div>
                <div class="modal-body border-bottom border-top">
                    <form id="frmAddCategory" action="{{route('category.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-12 text-center">
                                <label for="image">
                                    <img src="{{asset("images/defaults/broken.jpg")}}"
                                         class="rounded-circle img-fluid shadow"
                                         style="width: 100px; height: 100px; object-fit: cover; cursor: pointer"
                                         alt="Category Image"
                                         id="imgPrev"
                                    >
                                </label>
                                <input type="file" onchange="previewImage(event)" class="d-none" name="image" id="image">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="name" class="control-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Category Name" maxlength="50">
                                </div>
                                <div class="col">
                                    <label for="tariff" class="control-label">Tariff</label>
                                    <input type="text" class="form-control" id="tariff" name="tariff" placeholder="Enter Tariff" maxlength="50">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer pt-3 d-flex align-items-center justify-content-end">
                    <div class="float-right">
                        <button type="reset" class="btn btn-danger" form="frmAddCategory" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="frmAddCategory">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--@section('script')--}}
{{--    <script>--}}
{{--        document.getElementById("btnCreateNewCategory").addEventListener("click", function () {--}}
{{--            $("#createNewCategoryModal").modal("show");--}}
{{--        })--}}

{{--        function previewImage(event) {--}}
{{--            const [file] = event.target.files--}}
{{--            if (file) {--}}
{{--                document.getElementById("imgPrev").src = URL.createObjectURL(file)--}}
{{--            }--}}
{{--        }--}}
{{--    </script>--}}
{{--@endsection--}}
