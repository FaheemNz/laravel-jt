<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Validation;
use Illuminate\Validation\ValidationException;
use App\Report;
use App\Order;
use App\Offer;
use Validator;
use Auth;

class ReportController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $validator = Validator::make($request->all(), [
            'reason'                => 'required|in:reason1,reason2,reason3,reason4,reason5,other',
            'description'           => 'required|string|max:200',
            'type'                  => 'required|in:order,offer,user_query',
            'entity_id'             => 'nullable'
        ]);

        \Log::info("============ REPORT ===========");
        \Log::info(json_encode($request->all()));
        \Log::info("USERID:".auth()->user()->id);
        
        if ($validator->fails()) {
            \Log::info("============ VALIDATOR FAILED ===========");
            return $this->sendError('Validation failed', $validator->errors()->all(), 400);
        }
        
        if($request->type == 'order') {
            $order = Order::where('id',$request->entity_id)->where('user_id','!=',auth()->user()->id)->first();
            if(!$order) {
                $validator = Validator::make([], []);
                $validator->errors()->add('error', 'No such order found');
                throw new ValidationException($validator);
            }
        } else if($request->type == 'offer') {
            $offer = Offer::find($request->entity_id);
            
            if(!$offer) {
                $validator = Validator::make([], []);
                $validator->errors()->add('error', 'No such offer found');
                throw new ValidationException($validator);
            }
        }
        \Log::info("============ VALIDATION THEEK HAY BHAI ===========");

        $report = Report::updateOrCreate(
        ['entity_id' => $request->entity_id,'user_id' => $request->user_id,'type' => $request->type],
        [
            'reason'                => $request->reason,
            'description'           => $request->description,
            'entity_id'             => $request->entity_id,
            'user_id'               => Auth::user()->id,
        ]);

        return $this->sendResponse($report,'Report created/updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Api\ReportOrderController  $reportOrderController
     * @return \Illuminate\Http\Response
     */
    public function show(ReportOrderController $reportOrderController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Api\ReportOrderController  $reportOrderController
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportOrderController $reportOrderController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Api\ReportOrderController  $reportOrderController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportOrderController $reportOrderController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Api\ReportOrderController  $reportOrderController
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportOrderController $reportOrderController)
    {
        //
    }

    public function getReasons() {
        $reasons = ['reason1','reason2','reason3','reason4','reason5','other'];
        return $this->sendResponse($reasons,'Get report reasons successfully');
    }
}
