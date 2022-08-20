<?php

namespace App\Http\Controllers;

use App\Dispute;
use App\Notifications\TestNotification;
use App\Order;
use App\Trip;
use App\User;
use App\Utills\Constants\OrderStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_users    = User::count();
        $total_orders   = Order::count();
        $total_trips    = Trip::count();
        $total_profit   = number_format(Order::whereDate('updated_at',Carbon::today())->sum("profit"));

        $yearly_order_profit_report = $this->getOrderProfitYearlyReport(Order::query());

        $disputes = Dispute::latest()->take(5)->get();
        return view('dashboard', compact('total_users', 'total_orders', 'total_trips', 'total_profit', 'disputes', 'yearly_order_profit_report'));
    }

    private function getOrderProfitYearlyReport($modal)
    {
        $payments = $modal->select([
            DB::raw('COUNT(id) as quantity'),
            DB::raw('SUM(profit) as profit'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year')
        ])->groupBy(['year', 'month'])->get();

        return $payments;
    }


    public function socket()
    {
        return view('socket');
    }
}
