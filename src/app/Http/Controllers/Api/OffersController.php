<?php

namespace App\Http\Controllers\Api;

use App\Payment;
use App\Services\Interfaces\PaymentServiceInterface;
use App\SystemSetting;
use App\Utills\Constants\OfferStatus;
use App\Utills\Constants\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Offer;
use App\Order;
use App\Trip;
use App\Http\Requests\OfferRequest;
use App\Http\Resources\TravelerOrderResource;
use App\Lib\Helper;
use App\Notifications\OfferNotification;
use App\Reason;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @group Offers
 */
class OffersController extends BaseController
{
    protected $PaymentService;
    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->PaymentService = $paymentService;
    }

    /**
     * Create a new Offer for Order
     *
     * @bodyParam description string required Description for the offer
     * @bodyParam price integer required Price for the offer
     * @bodyParam reward integer required Reward price for the offer
     * @bodyParam expiry_date date required Expiry Date for the offer
     * @bodyParam order_id integer required Order ID
     * @bodyParam trip_id integer required Trip ID
     * @bodyParam currency_id integer ID of the currency (Must be same as Order's Currency)
     *
     * @authenticated
     * @response
     * {
     *      "success": true,
     *      "message": "Offers created successfully",
     *      "data"   : []
     * }
     *
     * @response 422
     * {
     *      "success": false,
     *      "message": "You cant create offer on your own order",
     *      "data"   : []
     * }
     */
    public function store(OfferRequest $offerRequest)
    {

        $data = $offerRequest->validated();
        $data["expiry_date"] = date('Y-m-d', strtotime($data['expiry_date']));

        $order = Order::where('id', $data['order_id'])->first();

        if ($order->user_id == Auth::user()->id) {
            return $this->sendError('Offer create Error', ['You cant create offer on your own order']);
        }
        if ($order->status != 'new') {
            return $this->sendError('Offer create Error', ['Cant create offer for this order now']);
        }

        $offer = Offer::where('user_id', auth()->id())
                ->where('order_id', $data['order_id'])
                ->first();

        $customer_service_charges_percentage = SystemSetting::where("key", "customer_service_charges_percentage")->first();
        if($customer_service_charges_percentage){
            $customer_service_charges_percentage = $customer_service_charges_percentage->value/100;
        }else{
            $customer_service_charges_percentage = 0.05;
        }

        if ($offer) {
            if(!in_array($offer->status, [OfferStatus::CLOSED]) &&
                $offer->counterOffer &&
                $offer->counterOffer->status == OfferStatus::CLOSED
            )
            {
                $new_data = [
                        'status'          =>    OfferStatus::OPEN,
                        'price'           =>    $data['price'],
                        'reward'          =>    $data['reward'],
                        'service_charges' =>    $data['price']  * $customer_service_charges_percentage,
                        'expiry_date'     =>    $data['expiry_date'],
                    ];

                $offer->update($new_data);
            }

            return $this->sendError('Offer create Error', ['Cant create offer for this order, offer has already been created.']);
        }

        $trip = Trip::where('id', $data['trip_id'])
            ->where('arrival_date', '>=', date('Y-m-d'))
            ->first();

        if (!$trip) {
            return $this->sendError('Offer create Error', ['Cant create offer, Trip already arrived']);
        }
        if ($trip->status != 'active') {
            return $this->sendError('Offer create Error', ['Cant create offer, Trip is in-active']);
        }
        if ($data['expiry_date'] > $trip->arrival_date) {
            return $this->sendError('Offer create Error', ['Cant create offer, Expiry date must be less than Trip arrival date']);
        }
        if ($trip->arrival_date >= $order->needed_by) {
            return $this->sendError('Offer create Error', ['Cant create offer, Trip arrival (' . $order->arrival_date . ') date must be less than Order needed by (' . $order->needed_by . ') date']);
        }
        if (($order->from_city_id != $trip->from_city_id) || ($order->destination_city_id != $trip->destination_city_id)) {
            return $this->sendError('Offer create Error', ['Cant create offer for this order because source and destination cities are not matched']);
        }


        $offer = Offer::create([
            'price'           =>    $data['price'],
            'reward'          =>    $data['reward'],
            'service_charges' =>    $data['price']  * $customer_service_charges_percentage,
            'currency_id'     =>    $order->currency_id,
            'status'          =>    'open',
            'expiry_date'     =>    $data['expiry_date'],
            'user_id'         =>    auth()->id(),
            'order_id'        =>    $data['order_id'],
            'trip_id'         =>    $data['trip_id']
        ]);

        Offer::sendNotification(
            $offer,
            $order->user_id,
            'create',
            $order->thumbnail,
            $order->name
        );

        return $this->sendResponse(
            new TravelerOrderResource($offer->order),
            'Offer Sent Successfully',
            201
        );
    }

    /**
     * Update offer
     *
     * @urlParam id integer required ID of the trip
     *
     * @bodyParam description string required Description for the offer
     * @bodyParam price integer required Price for the offer
     * @bodyParam reward integer required Reward price for the offer
     * @bodyParam expiry_date date required Expiry Date for the offer
     * @bodyParam order_id integer required Order ID
     * @bodyParam trip_id integer required Trip ID
     *
     * @response
     * {
     *      "success": true,
     *      "message": "Offer updated successfully",
     *      "data"   : []
     * }
     *
     * @response 404
     * {
     *      "success": false,
     *      "message": "Offer Error",
     *      "data"   : "No such offer found"
     * }
     *
     * @response 400
     * {
     *      "success": false,
     *      "message": "Offer Error",
     *      "data"   : "Cant update offer now! No order exists for this offer"
     * }
     */
    public function update(OfferRequest $offerRequest, int $id)
    {
        $offer = Offer::with('order')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();


        if (!$offer) {
            return $this->sendError('Offer Error', ['No such offer found'], 404);
        }
        $order =  $offer->order;
        if (!$order) {
            return $this->sendError('Offer Error', ['Cant update offer now! No order exists for this offer']);
        }
        // if ($offer->status != 'open' || $offer->status != 'rejected') {
        //     return $this->sendError('Offer Error', ['You cant revise this offer at this moment']);
        // }

        $validatedData = $offerRequest->validated();

        $validatedData['expiry_date'] = date('Y-m-d', strtotime($validatedData['expiry_date']));

        $customer_service_charges_percentage = SystemSetting::where("key", "customer_service_charges_percentage")->first();
        if($customer_service_charges_percentage){
            $customer_service_charges_percentage = $customer_service_charges_percentage->value/100;
        }else{
            $customer_service_charges_percentage = 0.05;
        }

        $offer->update([
            'price'             =>      $validatedData['price'],
            'reward'            =>      $validatedData['reward'],
            'service_charges'   =>      $validatedData['price']  * $customer_service_charges_percentage,
            'currency_id'       =>      $offer->order->currency_id,
            'expiry_date'       =>      $validatedData['expiry_date'],
            'trip_id'           =>      $validatedData['trip_id'],
            'status'            =>      OfferStatus::OPEN,
        ]);

        Offer::sendNotification(
            $offer,
            $order->user_id,
            'updated',
            $order->thumbnail,
            $order->name
        );

        return $this->sendResponse(
            new TravelerOrderResource($offer->order),
            'Offer updated successfully'
        );
    }

    public function selected(Offer $offer)
    {
        if($offer->order->user_id != auth()->id()){
            return $this->sendError('Offer Error', ['You are not authorized to select this offer'], 403);
        }

        if($offer->order->status != OrderStatus::NEW){
            return $this->sendError('Offer Error', ['Can\'t select this offer! Order already accepted']);
        }

        if($offer->status != OfferStatus::CLOSED ||
            (
                $offer->counterOffer &&
                $offer->counterOffer->status != OfferStatus::ACCEPTED
            )
        ){
            return $this->sendError('Offer Error', ['Can\'t select this offer! Among offer and counter offer one must be accepted first']);
        }

        $offer->order->update([
            "status" => OrderStatus::PAYMENT_IN_PROGRESS
        ]);

        $offer->counterOffer->update([
            "status" => OfferStatus::PAYMENT_IN_PROGRESS
        ]);

        // Create Payment For Order
        $this->PaymentService->createOrderPayment($offer->order);

        return $this->sendResponse(
            ['Counter offer have been selected successfully'], 'Success, Counter Offer Selected'
        );

    }

    /**
     * Reject Offer
     *
     * @authenticated
     * @urlParam id required ID of the offer
     * @bodyParam reason_id required Reason ID
     *
     * @response
     * {
     *      "success": true,
     *      "message": "Offer has been rejected successfully.",
     *      "data"   : []
     * }
     *
     * @response 404
     * {
     *      "success": false,
     *      "message": "No such offer found",
     *      "data"   : []
     * }
     *
     * @response 400
     * {
     *      "success": false,
     *      "message": "Cant delete offer now",
     *      "data"   : []
     * }
     *
     * @response 401
     * {
     *      "success": false,
     *      "message": ["You are not authorized to reject this offer"],
     *      "data"   : []
     * }
     */
    public function destroy(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'reason_id' => 'required|integer|exists:reasons,id'
        ], [
            'reason_id.required'    =>      'Reason.ID is required',
            'reason_id.integer'     =>      'Reason.ID is not valid',
            'reason_id.exists'      =>      'Reason.ID does not exist in our system'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Offer Error', $validator->errors()->all(), 422);
        }

        $offer = Offer::where('id', $id)
            ->where('status', '!=', OfferStatus::REJECTED)
            ->first();

        if (!$offer) {
            Helper::log('### Offer - Offer_Rejection_404 ###', [
                'user_id' => auth()->id(),
                'offer_id' => $id
            ]);

            return $this->sendError('Offer Error', ['No such offer found or the offer has already been rejected'], 400);
        }

        Helper::log('### Offer - Reject_Offer_Start ###', '');

        $order = Order::findOrFail($offer->order_id);

        if (auth()->id() != $order->user_id) {
            Helper::log('### Offer - Offer_Rejection_403 ###', [
                'user_id'  => auth()->id(),
                'offer_id' => $id,
                'order_id' => $order->id
            ]);

            return $this->sendError('Offer Error', ['You are not authorized to reject this offer'], 403);
        }

        $reason = Reason::findOrFail($request->reason_id);

        $offer->update([
            'status' => 'rejected',
            'reason_text' => $reason->description ?? null
        ]);

        Offer::sendNotification(
            $offer,
            $offer->user_id,
            'reject',
            $order->thumbnail,
            $order->user->first_name . " rejected your offer on ". $order->name,
            $reason->description
        );

        return $this->sendResponse(
            [],
            'Offer has been rejected successfully'
        );

        Helper::log('### Offer - Reject_Offer_End ###', '');
    }
}
