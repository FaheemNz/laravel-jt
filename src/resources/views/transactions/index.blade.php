@extends('layouts.main')
@section('content')
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Transactions</h4>
                        <div class="col-12 mt-2">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Source</th>
                                        <th>Details</th>
                                        <th>Amount</th>
                                        <th class="text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                        <tr>
                                            <td>{{$transaction->id}}</td>
                                            <td>{{$transaction->user->first_name}} {{$transaction->user->last_name}}</td>
                                            <td><span class="badge badge-pill badge-info">{{$transaction->source}}</span></td>
                                            <td>{{$transaction->transaction_details}}</td>
                                            <td>{{$transaction->currency->symbol}} {{number_format($transaction->amount)}}</td>
                                            <td class="text-right"><span class="badge badge-pill badge-warning">{{$transaction->status}}</span></td>
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
