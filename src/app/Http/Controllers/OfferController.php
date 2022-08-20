<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Offer;
use DataTables;
use Validation;
use Illuminate\Validation\ValidationException;
use Validator;
class OfferController extends BaseController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $data = Offer::withTrashed()->latest();
            return DataTables::of($data)
            ->filter(function ($instance) use ($request) {
                if($request->get('status') == '0') {
                    $instance->onlyTrashed();
                }
                if (!empty($request->get('order_id'))) {
                    $instance->where(function($w) use($request){
                       $order_id = $request->get('order_id');
                       $w->where('order_id',$order_id);
                   });
                }
                if($request->get('is_disabled') == '1') {
                    $instance->where(function($w) use($request){
                        $w->where('is_disabled',true);
                    });
                } else {
                    $instance->where(function($w) use($request){
                        $w->where('is_disabled',false);
                    });
                }
            })
            ->setRowAttr([
                'style' => function($row)  use ($request){
                    if($request->get('status') != '0') {
                        return $row->trashed() ? 'background-color: #ff6666;' : '';
                    }
                }
            ])
            ->addIndexColumn()
            ->addColumn('currency', function($row){
                $currency = null;
                if($row->currency) {
                    $currency = "<span><i class='now-ui-icons business_money-coins'></i> {$row->currency->short_code}</span>";
                }
                return $currency;
            })
            ->addColumn('status', function($row){
                $status = null;
                if($row->status) {
                    $status = "<span><i class='now-ui-icons business_money-coins'></i> {$row->status}</span>";
                }
                return $status;
            })
            ->addColumn('is_disabled', function($row){
                if ($row->is_disabled == true) {
                    $status = '<span class="badge badge-danger">Yes</span>';
                } else {
                    $status = '<span class="badge badge-success">No</span>';
                }
                return $status;
            })
            ->addColumn('offer_by', function($row){
                $offer_by = null;
                if ($row->createdBy) {
                    $offer_by = $row->createdBy->first_name.$row->createdBy->last_name;
                }
                return $offer_by;
            })
            ->addColumn('created_at', function($row){
                $created_at = null;
                if ($row->created_at) {
                    $created_at = date('Y-m-d H:i',strtotime($row->created_at));
                }
                return $created_at;
            })
            ->addColumn('source_city', function($row){
                $source_city = null;
                if ($row->trip) {
                    if($row->trip->getCompleteSourceAddressAttribute()) {
                        $source_city = $row->trip->getCompleteSourceAddressAttribute();
                    }
                }
                return $source_city;
            })
            ->addColumn('destination_city', function($row){
                $destination_city = null;
                if ($row->trip) {
                    if($row->trip->getCompleteDestinationAddressAttribute()) {
                        $destination_city = $row->trip->getCompleteDestinationAddressAttribute();
                    }
                }
                return $destination_city;
            })
            ->addColumn('arrival_date', function($row){
                $arrival_time = null;
                if ($row->trip) {
                  $arrival_time = $row->trip->arrival_date;
                }
                return $arrival_time;
            })
            ->addColumn('action', function($row){
                $btn = '';
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editOffer">Edit</a>';
                $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteOffer">Delete</a>';
                $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Disable" class="btn btn-warning btn-sm disableOffer">Disable</a>';
                $btn = $btn.'<a href="'.url('offers').'/'.$row->id.'" class="btn btn-info btn-sm detailOffer">Details</a>';
                return $btn;
            })
            ->rawColumns(['currency','status','is_disabled','offer_by','source_city','destination_city','created_at','arrival_date','action'])
            ->make(true);
        }
        $offers = Offer::latest()->get();
        return view('offer.index',compact('offers'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->offer_id == '' || $request->offer_id == null) {
            return response()->json(null);
        } else {
            $validator = Validator::make($request->all(), [
                'description'           => 'required|string|max:200',
                'status'                => 'required|string',
                'price'                 => 'required|numeric|gt:0.1',
                'reward'                => 'required|numeric|gt:0.1',
                'service_charges'       => 'required|numeric|gt:0.1',
                'currency'              => 'required|exists:currencies,id',
                'expiry_date'           => 'nullable|date_format:Y-m-d',
                'admin_review'          => 'nullable|string',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $is_disabled = false;
        if( $request->has('is_disabled') ){
            $is_disabled = true;
        }

        $offer = Offer::updateOrCreate([
            'id' => $request->offer_id
        ],[
            'description'           => $request->description,
            'status'                => $request->status,
            'price'                 => $request->price,
            'reward'                => $request->reward,
            'service_charges'       => $request->service_charges,
            'currency_id'           => $request->currency,
            'expiry_date'           => $request->expiry_date,
            'from_city_id'          => $request->from_city_id,
            'is_disabled'           => $is_disabled,
            'admin_review'          => $request->admin_review,
        ]);

        return response()->json(['success' => ['Offer updated successfully']], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = Offer::find($id);
        return view('offer.detail',compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $offer = Offer::with('createdBy')->where('id',$id)->first();
        return response()->json($offer);
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function disabled($id)
    {
        $offer = Offer::find($id);
        if($offer) {
            $offer->is_disabled = !$offer->is_disabled;
            $offer->save();
        }

        return response()->json(['success'=>'Offer disabled/active successfully.']);
    }

    public function destroy($id)
    {
        $offer = Offer::find($id);
        if($offer) {
            if($offer->status == 'accepted' && $offer->order->status != 'new')
                $validator = Validator::make([], []);
                $validator->errors()->add('error', 'Offer cant be deleted,Please complete the order process');
                throw new ValidationException($validator);
                $offer->delete();
        }
        return response()->json(['success'=>'Offer deleted successfully.']);
    }
}
