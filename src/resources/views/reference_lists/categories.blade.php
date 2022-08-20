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
                        <a  class="btn btn-primary btn-round pull-right text-white "
                            href="javascript:void(0)"
                            id="btnCreateNewCategory"
                        >
                            Add Category
                        </a>
                        <h5 class="title">Categories</h5>
                        <br>
                        <p class="category">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('referenceList')}}">Reference lists</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Category</li>
                                </ol>
                            </nav>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Tariff</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <img src="{{$category->image_url}}" class="img-fluid" width="50" alt="Image">
                                        </td>
                                        <td>{{$category->name}}</td>
                                        <td>{{$category->tariff}}</td>
                                        <td class="text-right d-flex align-items-center justify-content-end">
                                            <a href="#" class="btn btn-warning mr-2"><i class="now-ui-icons design-2_ruler-pencil text-dark font-weight-bold"></i></a>
                                            <form action="{{route('category.destroy', $category)}}" method="post">
                                                @csrf
                                                @method("DELETE")
                                                <button class="btn btn-danger"><i class="now-ui-icons ui-1_simple-remove text-light font-weight-bold"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
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

@section('script')
    <script>
        document.getElementById("btnCreateNewCategory").addEventListener("click", function () {
            $("#createNewCategoryModal").modal("show");
        })

        function previewImage(event) {
            const [file] = event.target.files
            if (file) {
                document.getElementById("imgPrev").src = URL.createObjectURL(file)
            }
        }
    </script>
@endsection
