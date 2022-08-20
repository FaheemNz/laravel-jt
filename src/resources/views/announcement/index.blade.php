@extends('layouts.main')
@section('content')
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Send Announcement</h4>
                        <div class="col-12 mt-2">
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route("announcement.store")}}" method="post" class="form-horizontal" enctype='multipart/form-data'>
                        @csrf

                            <div class="form-group col-12">
                                <label for="category" class="control-label">Users</label>
                                <select class="selectpicker form-control show-tick"
                                        name="users[]"
                                        data-live-search="true"
                                        data-size="5"
                                        data-style="btn btn-outline-primary btn-round btn-block"
                                        title="Select Users"
                                        multiple>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="last_name" class="control-label">Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="Enter Title" value="{{old("title")}}" maxlength="50">
                                </div>
                                <div class="form-group col-6">
                                    <label for="last_name" class="control-label">Description</label>
                                    <input type="text" class="form-control" name="description" placeholder="Enter Description" value="{{old("description")}}" maxlength="50">
                                </div>
                            </div>
                            <input type="submit" value="Send" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
