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
                        <h5 class="title">System Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                    <tr>
                                        <td>ID</td>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Description</th>
                                        <td class="text-right">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($settings as $setting)
                                        <form action="{{route("setting.update", $setting)}}" method="post">
                                            @csrf
                                            <tr>
                                                <th>{{$setting->id}}</th>
                                                <td>{{\Illuminate\Support\Str::replace("_"," ", \Illuminate\Support\Str::title($setting->key))}}</td>
                                                <td><span class="badge badge-pill bg-warning">{{\App\Utills\Constants\SystemSettingsType::ALL[$setting->type]}}</span></td>
                                                <td>
                                                    @if($setting->type == \App\Utills\Constants\SystemSettingsType::POLICY)
                                                        <textarea name="value" class="form-control" placeholder="Provide Value" cols="30"  rows="10">{{$setting->value}}</textarea>
                                                    @else
                                                        <input type="text" name="value" placeholder="Provide Value" class="form-control" value="{{$setting->value}}">
                                                    @endif
                                                </td>
                                                <td>
                                                    <textarea name="description" class="form-control" placeholder="Provide Description Here..." cols="30"  rows="10">{{$setting->description}}</textarea>
                                                </td>
                                                <td class="text-right">
                                                    <button type="submit" class="btn btn-primary text-warning">Update</button>
                                                </td>
                                            </tr>
                                        </form>
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
@endsection
