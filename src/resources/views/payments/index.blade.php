@extends('layouts.main')
@section('content')
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pending Payments</h4>
                        <div class="col-12 mt-2">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="customDataTable table table-striped">
                                <thead class="bg-warning text-primary">
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Traveller</th>
                                    <th>Brrring Profit</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td>
                                                <img src="{{asset('images/'.$order['thumbnail'])}}" style="width: 50px; height: 50px; object-fit: cover" class="rounded-circle img-fluid" alt="Avatar">
                                            </td>
                                            <td>
                                                <a href="{{route('orders.show', $order['id'])}}" target="_blank">
                                                    {{$order['name']}}
                                                </a>
                                            </td>
                                            <td>
                                                <p class="d-flex align-items-center justify-content-between">
                                                    <span>Name: </span>
                                                    <a href="{{route('users.show', $order['createdBy']['id'])}}" target="_blank">
                                                        {{$order['createdBy']['fullName']}}
                                                    </a>
                                                </p>
                                                <p class="d-flex align-items-center justify-content-between">
                                                    <span>Returnable: </span>
                                                    <span>{{$order['basePriceCurrency'] . ' ' . $order['customer_returnable']['total_returnable']}}</span>
                                                </p>
                                                @if(!$order['is_customer_paid'])
                                                    <small>
                                                        <a href="{{route('clear-customer-payment', $order['id'])}}" class="text-muted text-center">Click Here To Pay Customer</a>
                                                    </small>
                                                @else
                                                    <span class="badge badge-pill badge-success">PAID</span>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="d-flex align-items-center justify-content-between">
                                                    <span>Name: </span>
                                                    <a href="{{route('users.show', $order['offer']['createdBy']['id'])}}" target="_blank">
                                                        {{$order['offer']['createdBy']['fullName']}}
                                                    </a>
                                                </p>
                                                <p class="d-flex align-items-center justify-content-between">
                                                    <span>Returnable: </span>
                                                    <span>{{$order['basePriceCurrency'] . ' ' . $order['traveller_payable']['total_receivable']}}</span>
                                                </p>
                                                @if(!$order['is_traveler_paid'])
                                                    <small>
                                                        <a href="{{route('clear-traveller-payment', $order['id'])}}" class="text-muted text-center">Click Here To Pay Traveller</a>
                                                    </small>
                                                @else
                                                    <span class="badge badge-pill badge-success">PAID</span>
                                                @endif
                                            </td>
                                            <td class="text-right">

                                                {{$order['basePriceCurrency'] . ' ' . $order['brrring']['profit']}}
                                            </td>
                                            <td><span class="badge badge-pill badge-warning">{{$order['status']}}</span></td>
                                            <td class="text-right">
                                                <a href="{{route('payment.clear', $order['id'])}}" class="btn btn-info"><i class="now-ui-icons business_money-coins text-dark font-weight-bold"></i></a>
{{--                                                <a href="#" class="btn btn-info"><i class="now-ui-icons gestures_tap-01 text-dark font-weight-bold"></i></a>--}}
{{--                                                <a href="#" class="btn btn-warning"><i class="now-ui-icons design-2_ruler-pencil text-dark font-weight-bold"></i></a>--}}
{{--                                                <a href="#" class="btn btn-danger"><i class="now-ui-icons ui-1_simple-remove text-light font-weight-bold"></i></a>--}}
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
