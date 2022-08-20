<?php

namespace App\Http\Controllers\Api;

use App\CounterOffer;
use App\Http\Controllers\BaseController;
use App\Http\Requests\CounterOfferRequest;
use App\Notifications\CounterOfferNotification;
use App\Offer;
use App\Order;
use App\Reason;
use App\Utills\Constants\OfferStatus;
use App\Utills\Constants\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @group Counter Offer
 */
class CounterOfferController extends BaseController
{
    /**
     * Create a Counter Offer for Order
     *
     * @urlParam offer_id integer required ID of the offer
     * @bodyParam reward integer required Reward price for the offer
     * @bodyParam expiry_date date required Expiry Date for the offer
     *
     * @authenticated
     * @response
     * {
     *      "success": true,
     *      "message": "Counter Offer has been created successfully",
     *      "data"   : []
     * }
     *
     * @response 401
     * {
     *      "success": false,
     *      "message": "You cant create Counter offer as you dont own this order",
     *      "data"   : []
     * }
     *
     * @response 400
     * {
     *      "success": false,
     *      "message": "A Counter Offer already exists for this offer!",
     *      "data"   : []
     * }
     */
    public function store(int $id, CounterOfferRequest $counterOfferRequest)
    {
        $validatedData = $counterOfferRequest->validated();

        $data = [
            'reward'        => $validatedData['reward'],
            'expiry_date'   => date('Y-m-d', strtotime($validatedData['expiry_date']))
        ];

        $offer = Offer::findOrFail($id);
        $order = Order::findOrFail($offer->order_id);


        if( auth()->id() != $order->user_id ){
            return $this->sendError(
                'Counter Offer Error',
                ['You cant create Counter offer as you dont own this order'],
                401
            );
        }

        if($order->status != OrderStatus::NEW){
            return $this->sendError('Counter Offer Create Error', ['Cant create counter offer for this order now']);
        }

        $counter_offer = CounterOffer::where('user_id', auth()->id())
            ->where('offer_id', $id)
            ->first();


        if($counter_offer){
            if($counter_offer->offer->status == OfferStatus::OPEN ){
                $counter_offer->offer->update(["status" => OfferStatus::CLOSED]);
                $counter_offer->update(["status" => OfferStatus::OPEN,  'reward' =>  $data['reward'], 'expiry_date' =>  $data['expiry_date']]);
            }

            return $this->sendResponse(
                ['Counter-Offer Sent Successfully'],
                'Counter Offer Success',
                201);
        }

        $counterOffer = CounterOffer::updateOrCreate(
            ['offer_id' => $offer->id],
            [
                'reward'            =>  $data['reward'],
                'currency_id'       =>  $offer->currency_id,
                'status'            =>  'open',
                'expiry_date'       =>  $data['expiry_date'],
                'user_id'           =>  Auth::user()->id,
                'order_id'          =>  $offer->order_id,
                'trip_id'           =>  $offer->trip_id,
                'offer_id'          =>  $offer->id
            ]
        );

        $offer->update([
            'status' => 'closed'
        ]);

        CounterOffer::sendNotification(
            $counterOffer,
            $offer->user_id,
            'create',
            $order->thumbnail,
            $order->name
        );

        return $this->sendResponse(
            ['Counter-Offer Sent Successfully'],
            'Counter Offer Success',
            201
        );
    }

    /**
     * Reject a Counter Offer
     *
     * @urlParam id integer required ID of the counter offer
     * @bodyParam reason_id integer required ID of the Reason
     * @bodyParam counter_offer_id integer required ID of the counter offer
     *
     * @authenticated
     * @response
     * {
     *      "success": true,
     *      "message": "Counter Offer rejected successfully",
     *      "data"   : []
     * }
     *
     * @response 401
     * {
     *      "success": false,
     *      "message": "You are not authorized to reject counter offer for this Order!",
     *      "data"   : []
     * }
     */
    public function destroy(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'counter_offer_id'   => 'required|integer',
            'reason_id'         => 'required|integer'
        ], [
            'counter_offer_id.integer'  => 'Please provide a valid Counter Offer ID',
            'reason_id.integer'         => 'Please provide a valid Reason ID'
        ]);

        if($validator->fails()){
            return $this->sendError('Counter Offer Error', $validator->errors()->all(), 422);
        }

        $counterOffer = CounterOffer::findOrFail($request->counter_offer_id);

        $offer = Offer::findOrFail($counterOffer->offer_id);

        if( auth()->id() != $offer->user_id ){
            return $this->sendError('Counter Offer Error', ['You are not authorized to reject counter offer for this Order!'], 401);
        }

        $reason = Reason::findOrFail($request->reason_id);

        $counterOffer->offer()->update([
            'status' => 'open'
        ]);

        $counterOffer->status = 'closed';
        $counterOffer->description = $reason->description;
        $counterOffer->save();

        CounterOffer::sendNotification(
            $counterOffer,
            Order::findOrFail($counterOffer->order_id)->user_id,
            'reject',
            $counterOffer->order->thumbnail,
            $counterOffer->offer->user->first_name . " rejected your counter offer on ". $counterOffer->order->name,
            $reason->description
        );

        return $this->sendResponse(
            ['Counter Offer has been rejected successfully'],
            'Counter Offer Success'
        );
    }

    /**
     * Accept Counter Offer
     *
     * @urlParam id integer required ID of the offer
     * @bodyParam counter_offer_id integer required ID of the counter offer
     *
     * @authenticated
     * @response
     * {
     *      "success": true,
     *      "message": "Counter Offer has been accepted successfully",
     *      "data"   : []
     * }
     *
     * @response 401
     * {
     *      "success": true,
     *      "message": "You are not authorized to accept counter offer for this Order!",
     *      "data"   : []
     * }
     *
     * @response 400
     * {
     *      "success": true,
     *      "message": "This Counter Offer has already been Rejected",
     *      "data"   : []
     * }
     */
    public function accept(Request $request, int $id)
    {
        $counterOfferId = $request->counter_offer_id;

        if(!is_numeric($counterOfferId)){
            return $this->sendError('Counter Offer Error', ['Please provide a valid Counter Offer ID']);
        }

        $counterOffer = CounterOffer::findOrFail($counterOfferId);

        $offer = Offer::findOrFail($counterOffer->offer_id);

        if( auth()->id() != $offer->user_id ){
            return $this->sendError('Counter Offer Error', ['You are not authorized to accept counter offer for this Order!'], 401);
        }

        if($counterOffer->status == 'rejected'){
            return $this->sendError('Counter Offer Error', ['This Counter Offer has already been Rejected'], 400);
        }

        $counterOffer->status = 'accepted';
        $counterOffer->save();

        CounterOffer::sendNotification(
            $counterOffer,
            Order::findOrFail($counterOffer->order_id)->user_id,
            'accept',
            $counterOffer->order->thumbnail,
            $counterOffer->order->name
        );


        return $this->sendResponse(
            [$counterOffer],
            'Counter Offer has been accepted successfully',
            200
        );
    }
}
