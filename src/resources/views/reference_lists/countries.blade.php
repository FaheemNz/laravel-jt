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
                            id="btnCreateNewCountry"
                        >
                            Add Country
                        </a>
                        <h5 class="title">Countries</h5>
                        <br>
                        <p class="category">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('referenceList')}}">Reference lists</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Country</li>
                                </ol>
                            </nav>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Short Code</th>
                                    <th>Phone Code</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($countries as $country)
                                    <tr>
                                        <td>{{$country->id}}</td>
                                        <td>
                                            <img src="{{asset($country->flag_url)}}" width="50" class="mr-3" alt="flag">
                                            {{$country->name}}
                                        </td>
                                        <td>{{$country->short_code}}</td>
                                        <td>+{{$country->phone_code}}</td>
                                        <td class="text-right d-flex align-items-center justify-content-end">
                                            <a href="#" class="btn btn-warning mr-2"><i class="now-ui-icons design-2_ruler-pencil text-dark font-weight-bold"></i></a>
                                            <form action="{{route('country.destroy', $country)}}" method="post">
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
    <div class="modal fade" id="createNewCountryModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <div class="row">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <h5 class="modal-title" id="modelHeading">Add New Country</h5>
                    </div>
                </div>
                <div class="modal-body border-bottom border-top">
                    <form id="frmAddCountry" action="{{route('country.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="name" class="control-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Country Name" maxlength="50">
                                </div>
                                <div class="col">
                                    <label for="short_code" class="control-label">Short Code</label>
                                    <input type="text" class="form-control" id="short_code" name="short_code" placeholder="Enter Short Code" maxlength="50">
                                </div>
                                <div class="col">
                                    <label for="phone_code" class="control-label">Phone Code</label>
                                    <input type="text" class="form-control" id="phone_code" name="phone_code" placeholder="Enter Phone Code" maxlength="50">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer pt-3 d-flex align-items-center justify-content-end">
                    <div class="float-right">
                        <button type="reset" class="btn btn-danger" form="frmAddCountry" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="frmAddCountry">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        document.getElementById("btnCreateNewCountry").addEventListener("click", function () {
            $("#createNewCountryModal").modal("show");
        })
    </script>
@endsection
