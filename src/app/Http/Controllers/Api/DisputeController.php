<?php

namespace App\Http\Controllers\Api;

use App\Dispute;
use App\Http\Controllers\BaseController;
use App\Http\Resources\HomeOrderResource;
use App\Reason;
use App\Utills\Constants\ReasonType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisputeController extends BaseController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "reason_id"             => "required",
            "description"           => "required|string"
        ], [
            'reason_id.required'     =>  'A valid reason is required',
            'description.required'   =>  'A description is required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Dispute Error', $validator->errors());
        }

        $reason = Reason::find($request->reason_id);
    
        $message = null;

        if($reason->type == ReasonType::ORDER && !isset($request->order_id)){
            $message = "Order Id is required";
        }else if($reason->type == ReasonType::TRIP && !isset($request->trip_id)){
            $message = "Trip Id is required";
        }else if($reason->type == ReasonType::OFFER && !isset($request->offer_id)){
            $message = "Offer Id is required";
        }else if($reason->type == ReasonType::COUNTER_OFFER && !isset($request->counter_offer_id)){
            $message = "Counter Offer Id is required";
        }

        if($message){
            return $this->sendError('Dispute Error', $message);
        }

        $dispute = Dispute::create([
            "user_id"               => auth()->id(),
            "description"           => $request->description,
            "reason_id"             => $request->reason_id,
            "order_id"              => $request->order_id,
            "offer_id"              => $request->offer_id,
            "trip_id"               => $request->trip_id,
            "counter_offer_id"      => $request->counter_offer_id,
        ]);

        return $this->sendResponse(
            $dispute,
            'Orders retrieved successfully'
        );

    }
}
