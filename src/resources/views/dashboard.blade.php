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
                        <h4 class="card-title font-weight-bold">Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <!-- OverView Section -->
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <a href="{{route('users.index')}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Users</h3>
                                                <p class="fa-2x p-0 m-0 text-right font-weight-bold">{{$total_users}}</p>
                                            </div>
                                            <i class="fa-3x fa fa-users text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <a href="{{route('orders.index')}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Orders</h3>
                                                <p class="fa-2x p-0 m-0 text-right font-weight-bold">{{$total_orders}}</p>
                                            </div>
                                            <i class="fa-3x fa fa-boxes text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <a href="{{route('trips.index')}}" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Trips</h3>
                                                <p class="fa-2x p-0 m-0 text-right font-weight-bold">{{$total_trips}}</p>
                                            </div>
                                            <i class="fa-3x fa fa-plane-departure text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <a href="#" style="text-decoration: none" class="card rounded">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="p-0 m-0 text-left">Profit</h3>
                                                <p class="fa-2x p-0 m-0 text-right font-weight-bold">$ {{$total_profit}}</p>
                                            </div>
                                            <i class="fa-3x fa fa-chart-line text-warning"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Reports Section -->
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card card-chart">
                                    <div class="card-header">
                                        <h5 class="card-category">{{now()->format('Y')}} Profit</h5>
                                        <h4 class="card-title">Monthly Profit</h4>
{{--                                        <div class="dropdown">--}}
{{--                                            <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown" aria-expanded="false">--}}
{{--                                                <i class="now-ui-icons loader_gear"></i>--}}
{{--                                            </button>--}}
{{--                                            <div class="dropdown-menu dropdown-menu-right" style="">--}}
{{--                                                <a class="dropdown-item" href="#">Action</a>--}}
{{--                                                <a class="dropdown-item" href="#">Another action</a>--}}
{{--                                                <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                                                <a class="dropdown-item text-danger" href="#">Remove Data</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-area"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                            <canvas id="lineChartExample" width="706" height="380" style="display: block; height: 190px; width: 353px;" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card card-chart">
                                    <div class="card-header">
                                        <h5 class="card-category">{{now()->format('Y')}} Orders</h5>
                                        <h4 class="card-title">All Orders</h4>
{{--                                        <div class="dropdown">--}}
{{--                                            <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">--}}
{{--                                                <i class="now-ui-icons loader_gear"></i>--}}
{{--                                            </button>--}}
{{--                                            <div class="dropdown-menu dropdown-menu-right">--}}
{{--                                                <a class="dropdown-item" href="#">Action</a>--}}
{{--                                                <a class="dropdown-item" href="#">Another action</a>--}}
{{--                                                <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                                                <a class="dropdown-item text-danger" href="#">Remove Data</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-area"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                            <canvas id="lineChartExampleWithNumbersAndGrid" width="706" height="380" style="display: block; height: 190px; width: 353px;" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card card-chart">
                                    <div class="card-header">
                                        <h5 class="card-category">Email Statistics</h5>
                                        <h4 class="card-title">24 Hours Performance</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-area"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                            <canvas id="barChartSimpleGradientsNumbers" width="706" height="380" style="display: block; height: 190px; width: 353px;" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="now-ui-icons ui-2_time-alarm"></i> Last 7 days
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lists Section -->
                        <div class="row">
                            <div class="col-md-6 d-none">
                                <div class="card card-tasks">
                                    <div class="card-header ">
                                        <h5 class="card-category">Backend development</h5>
                                        <h4 class="card-title">Tasks</h4>
                                    </div>
                                    <div class="card-body ">
                                        <div class="table-full-width table-responsive">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" checked="">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-left">Sign contract for "What are conference organizers afraid of?"</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task">
                                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                                        </button>
                                                        <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove">
                                                            <i class="now-ui-icons ui-1_simple-remove"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-left">Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task">
                                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                                        </button>
                                                        <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove">
                                                            <i class="now-ui-icons ui-1_simple-remove"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" checked="">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-left">Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit
                                                    </td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task">
                                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                                        </button>
                                                        <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove">
                                                            <i class="now-ui-icons ui-1_simple-remove"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer ">
                                        <hr>
                                        <div class="stats">
                                            <i class="now-ui-icons loader_refresh spin"></i> Updated 3 minutes ago
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-category">Disputes</h5>
                                        <h4 class="card-title">Latest 5 Disputes</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead class=" text-primary">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Reason</th>
                                                    <th class="text-right">Dispute Info</th>
                                                </tr></thead>
                                                <tbody>
                                                    @forelse($disputes as $dispute)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center flex-column text-center">
{{--                                                                    <img src="{{$dispute->user->avatar}}" class="rounded-circle img-fluid mb-3" alt="Avatar">--}}
                                                                    <p>{{$dispute->user->first_name . " " . $dispute->user->last_name}}</p>
                                                                </div>
                                                            </td>
                                                            <td>{{$dispute->description}}</td>
                                                            <td>{{$dispute->reason->description}}</td>
                                                            <td>
                                                                <span class="badge badge-pill badge-warning">
                                                                    {{\App\Utills\Constants\ReasonType::ALL[$dispute->reason->type]}}
                                                                </span>
                                                                @if($dispute->order)
                                                                    <a href="{{route("orders.show", $dispute->order_id)}}" target="_blank">
                                                                        {{$dispute->order->name}}
                                                                    </a>
                                                                @endif
                                                                @if($dispute->trip)
                                                                    <a href="{{route("trips.show", $dispute->trip_id)}}" target="_blank">
                                                                        {{$dispute->trip->completeSourceAddress}}
                                                                    </a>
                                                                @endif
                                                                @if($dispute->offer)
                                                                    <a href="{{route("offers.show", $dispute->offer_id)}}" target="_blank">
                                                                        {{$dispute->offer->order->name}}  ({{$dispute->offer->order->currency->symbol}} {{$dispute->offer->reward}})
                                                                    </a>
                                                                @endif
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
                    <!-- end content-->
                </div>
                <!--  end card  -->
            </div>
        </div>
    </div>
    @include('partials.sidebar-footer')
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            let data = @json($yearly_order_profit_report);
            demo.initChartPageCharts(data);
        });
    </script>

{{--    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>--}}
{{--    <script>--}}
{{--        initiatePusher();--}}
{{--        function initiatePusher() {--}}
{{--            // Enable pusher logging - don't include this in production--}}
{{--            Pusher.logToConsole = true;--}}

{{--            let pusher = new Pusher('ccdc63ed3baee298d79f', {--}}
{{--                cluster: 'ap2'--}}
{{--            });--}}

{{--            let authUser = @json(auth()->id());--}}
{{--            var channel = pusher.subscribe('user_channel_'+authUser);--}}
{{--            channel.bind('user_event_'+authUser, function(data) {--}}
{{--                alert(JSON.stringify(data));--}}
{{--            });--}}
{{--        }--}}
{{--    </script>--}}

{{--    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>--}}
{{--    <script>--}}
{{--        const beamsClient = new PusherPushNotifications.Client({--}}
{{--            instanceId: '741d0a42-dbe1-475e-8672-c3a1e3b14eeb',--}}
{{--        });--}}

{{--        beamsClient.start()--}}
{{--            .then(() => beamsClient.setDeviceInterests(['debug-hello','hello','brrring_user']))--}}
{{--            .then(() => console.log('Successfully registered and subscribed!'))--}}
{{--            .catch(console.error);--}}
{{--    </script>--}}

@endsection
