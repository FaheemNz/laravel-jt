<?php

namespace App\Http\Controllers;

use App\Currency;
use App\SystemSetting;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\OrderRequest;
use App\Lib\Helper;
use App\Order;
use App\Offer;
use DataTables;
use Illuminate\Support\Facades\DB;
use Validation;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        if (request()->ajax()) {
//            $data = Order::withTrashed()->latest();
//            return DataTables::of($data)
//                    ->filter(function ($instance) use ($request) {
//                        if($request->get('status') == '0') {
//                            $instance->onlyTrashed();
//                        }
//                        if($request->get('is_disputed') == '1') {
//                            $instance->where(function($w) use($request){
//                                $w->where('is_disputed',true);
//                            });
//                        } else {
//                            $instance->where(function($w) use($request){
//                                $w->where('is_disputed',false);
//                            });
//                        }
//                    })
//                    ->setRowAttr([
//                        'style' => function($row)  use ($request){
//                            if($request->get('status') != '0') {
//                                return $row->trashed() ? 'background-color: #ff6666;' : '';
//                            }
//                        }
//                    ])
//                    ->addIndexColumn()
//                    ->addColumn('category', function($row){
//                        $category = null;
//                        if($row->category) {
//                            $category = $row->category->name;
//                        }
//                        return $category;
//                    })
//                    ->addColumn('currency', function($row){
//                        $currency = null;
//                        if($row->currency) {
//                            $currency = "<span><i class='now-ui-icons business_money-coins'></i> {$row->currency->short_code}</span>";
//                        }
//                        return $currency;
//                    })
//                    ->addColumn('url', function($row){
//                        $url = null;
//                        if($row->url) {
//                            $url = '<a href="'.$row->url.'"></a>';
//                        }
//                        return $url;
//                    })
//                    ->addColumn('with_box', function($row){
//                        if ($row->with_box == true) {
//                            $with_box = '<span class="badge badge-success">Yes</span>';
//                        } else {
//                            $with_box = '<span class="badge badge-warning">No</span>';
//                        }
//                        return $with_box;
//                    })
//                    ->addColumn('source_city', function($row){
//                        $source_city = null;
//                        $source_city = $row->getCompleteSourceAddressAttribute();
//                        return $source_city;
//                    })
//                    ->addColumn('weight', function($row){
//                        $weight = null;
//                        $weight = $row->getWeightString();
//                        return $weight;
//                    })
//                    ->addColumn('destination_city', function($row){
//                        $destination_city = null;
//                        $destination_city = $row->getCompleteDestinationAddressAttribute();
//                        return $destination_city;
//                    })
//                    ->addColumn('status', function($row){
//                        $status = null;
//                        if($row->status) {
//                            $status = "<span><i class='now-ui-icons business_money-coins'></i> {$row->status}</span>";
//                        }
//                        return $status;
//                    })
//                    ->addColumn('is_disputed', function($row){
//                        if ($row->is_disputed == true) {
//                            $status = '<span class="badge badge-danger">Yes</span>';
//                        } else {
//                            $status = '<span class="badge badge-success">No</span>';
//                        }
//                        return $status;
//                    })
//                    ->addColumn('is_disabled', function($row){
//                        if ($row->is_disabled == true) {
//                            $status = '<span class="badge badge-danger">Yes</span>';
//                        } else {
//                            $status = '<span class="badge badge-success">No</span>';
//                        }
//                        return $status;
//                    })
//                    ->addColumn('order_by', function($row){
//                        $order_by = null;
//                        if ($row->createdBy) {
//                            $order_by = $row->createdBy->first_name.$row->createdBy->last_name;
//                        }
//                        return $order_by;
//                    })
//                    ->addColumn('created_at', function($row){
//                        $created_at = null;
//                        if ($row->created_at) {
//                            $created_at = date('Y-m-d H:i',strtotime($row->created_at));
//                        }
//                        return $created_at;
//                    })
//                    ->addColumn('action', function($row){
//                        $btn = '';
//                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editOrder">Update</a>';
//                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteOrder">Delete</a>';
//                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Disable" class="btn btn-warning btn-sm disableOrder">Disable</a>';
//                        $btn = $btn.'<a href="'.url('orders').'/'.$row->id.'" class="btn btn-info btn-sm detailOrder">Details</a>';
//                        return $btn;
//                    })
//                    ->rawColumns(['category','currency','url','with_box','source_city','destination_city','status','is_disputed','is_disabled','order_by','created_at','action'])
//                    ->make(true);
//        }
        $orders = Order::latest()->get();
//        dd($orders[0]->user->avatar);
        return view('order.index',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OrderRequest $request)
    {
        $user_id = $request->user_id ?? auth()->id();
        $user = User::find($user_id);


        $duplicateOrderCheck = DB::select(
            "SELECT COUNT(*) AS is_duplicate
             FROM orders
             WHERE user_id = {$user_id} AND name = '{$request->name}'"
        );

        if ($duplicateOrderCheck[0]->is_duplicate > 0) {
            return $this->sendError('Duplicate Order', ['An order with the same name already exists, please try with different name'], 400);
        }

        $order_data = $request->validated();
        $order_data['user_id'] = $user_id;

        Order::create($order_data);

        return redirect()->route("orders.index");

//        if (is_null($request->order_id)) {
//            return response()->json(null);
//        }
//
//        $is_disputed = false;
//        $is_disabled = false;
//        $with_box    = false;
//        $is_doorstep_delivery = false;
//
//        if( $request->has('with_box') ){
//            $with_box = true;
//        }
//        if( $request->has('is_doorstep_delivery') ){
//            $is_doorstep_delivery = true;
//        }
//        if( $request->has('is_disputed') ){
//            $is_disputed = true;
//        }
//        if( $request->has('is_disabled') ){
//            $is_disabled = true;
//        }
//
//        Order::updateOrCreate([
//            'id' => $request->order_id
//        ],[
//            'name'                  =>      $request->name,
//            'description'           =>      $request->description,
//            'category_id'           =>      $request->category_id,
//            'url'                   =>      $request->url,
//            'weight'                =>      $request->weight,
//            'quantity'              =>      $request->quantity,
//            'price'                 =>      $request->price,
//            'currency_id'           =>      $request->currency,
//            'reward'                =>      $request->reward,
//            'with_box'              =>      $with_box,
//            'needed_by'             =>      $request->needed_by,
//            'from_city_id'          =>      $request->from_city_id,
//            'destination_city_id'   =>      $request->destination_city_id,
//            'status'                =>      $request->status,
//            'customer_rating'       =>      $request->customer_rating,
//            'traveler_rating'       =>      $request->traveler_rating,
//            'customer_review'       =>      $request->customer_review,
//            'traveler_review'       =>      $request->traveler_review,
//            'is_disputed'           =>      $is_disputed,
//            'is_disabled'           =>      $is_disabled,
//            'admin_review'          =>      $request->admin_review,
//            'is_doorstep_delivery'  =>      $request->is_doorstep_delivery
//        ]);
//
//        return response()->json(['success' => ['Order updated successfully']], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('order.detail',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::with('createdBy')->where('id',$id)->first();
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function disabled($id)
    {
        $order = Order::find($id);
        if($order) {
            $order->is_disabled = !$order->is_disabled;
            $order->save();
        }

        return response()->json(['success'=>'Order disabled/active successfully.']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        if($order->status != 'new') {
            $validator = Validator::make([], []);
            $validator
                ->errors()
                ->add('error', 'Order cant be deleted,Please complete the order process');

            throw new ValidationException($validator);
        }

        $order->delete();

        return response()->json(['success'=>'Order deleted successfully.']);
    }


    public function acceptOfferPayment(Request $request, $offer_id)
    {
        $offer = Offer::where('id', $offer_id)->first();
        $order= $offer->order;

        $ref_no = "O".$offer_id."T".time();

        $user_base_currency_id = $order->createdBy->currency->id;
        $currency_ids = $request->currency_ids;
        $deduct_amounts = $request->deduct_amounts;
        $payment_type = $request->payment_type;
        $bearer = $request->bearer;

        /**
         *
         * https://apis.brrring.co/api/v1/offers/51/process_payment?base_currency_id=87&currency_ids[]=87,deduct_amounts[]=10000,payment_type=credit-card&bearer=
         *
         */
        if(!$bearer) {
            return view('invalid_request_param');
        }

        if($order->images->first()) {
            $order->image = $order->images->first()->name;
        }

        $order->image = null;
        $order->category = $order->category()->first();
        $order->from = $order->getCompleteSourceAddressAttribute();
        $order->to = $order->getCompleteDestinationAddressAttribute();

        $offer_price = Helper::convertCurrency(
            $offer->currency_id,
            $user_base_currency_id,
            $offer->price
        );
        $offer_reward = Helper::convertCurrency(
            $offer->currency_id,
            $user_base_currency_id,
            $offer->reward
        );

        $order->price = $offer_price;
        $order->reward = $offer_reward;

        $customer_service_charges_percentage = SystemSetting::where("key", "customer_service_charges_percentage")->first();
        if($customer_service_charges_percentage){
            $customer_service_charges_percentage = $customer_service_charges_percentage->value/100;
        }else{
            $customer_service_charges_percentage = 0.05;
        }

        $order->duty_charges        = ($offer_price / 100) * $order->category->tariff; // TODO: multiply by order category charges
        $order->service_fee         = $offer_price * $customer_service_charges_percentage;
        $order->total_payable       = $offer_price + $offer_reward + $order->duty_charges + $order->service_fee;
        $order->total_payable_pkr   =  Helper::convertCurrency(
            $user_base_currency_id,
            87,
            $order->total_payable
        );

        //Wallets dudcted sum
        $deduct_amount = 0;
        $wallets = [];

        if($currency_ids && $deduct_amounts) {
            for ($i=0, $currency_count=count($currency_ids); $i<$currency_count; $i++) {
                $wallets[] = [
                    'currency_id' => $currency_ids[$i],
                    'deduct_amount' => $deduct_amounts[$i]
                ];

                $deduct_amount +=  Helper::convertCurrency(
                    $currency_ids[$i],
                    $user_base_currency_id,
                    $deduct_amounts[$i]
                );
            }

            $order->wallet_amount = $deduct_amount;
            $order->sub_total_payable = $order->total_payable;
            $order->total_payable -= $deduct_amount;
        }
        $order->user = User::with('currency')->where('id', $order->user_id)->get()->first();
        $user = User::find($order->user_id);

        if($payment_type == 'bank_account'){
            return view('process_payment_bankaccount', compact('order', 'offer', 'ref_no', 'wallets', 'bearer', 'payment_type'));
        }

        return view('process_payment', compact('order', 'offer', 'ref_no', 'wallets', 'bearer', 'payment_type'));
    }
}
