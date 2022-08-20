<?php

namespace App\Http\Controllers\Api;

use App\Lib\Helper;
use App\Services\Interfaces\ImageServiceInterface;
use App\Utills\Constants\FilePaths;
use App\Utills\Constants\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use App\Image;
use App\Order;

/**
 * @group Timeline
 */
class TimelineController extends BaseController
{

    protected $ImageService;
    public function __construct(ImageServiceInterface $imageService)
    {
        $this->ImageService = $imageService;
    }

    /**
     * Upload Item Purchased Receipt
     *
     * @bodyParam receipt image required A valid receipt image
     * @bodyParam amount integer required Amount value
     * @bodyParam order_id integer required Order ID
     *
     * @authenticated
     * @response {
     *  "success": true,
     *  "message": "Receipt upload Success",
     *  "data"   : ["Receipt upload successfully"]
     * }
     * @param Request $request
     * @return Response
     */
    public function uploadItemPurchasedReceipt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receipt'   =>      'required|image',
            'amount'    =>      'required|numeric|gt:0',
            'order_id'  =>      'required|integer'
        ], [
            'receipt.required'  =>  'Receipt is required',
            'receipt.image'     =>  'Receipt should be a valid image',
            'amount.required'   =>  'Amount is required',
            'amount.integer'    =>  'Amount value is not valid',
            'order_id.required' =>  'Order.ID is required',
            'order_id.integer'  =>  'Order.ID value is not valid'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Upload Receipt Error', $validator->errors()->all(), 422);
        }

        $order = Order::findOrFail($request->order_id);

        if($order->traveler_id != auth()->id()){
            return $this->sendError('You are not allowed to do this action', ["Not Authorized"], 422);
        }

        if($order->pin_code){
            return $this->sendError('Item Purchased Receipt Error', ["Can't update purchased receipt, item handed over initiated"], 422);
        }

        if($order->item_purchased_receipt){
            $this->ImageService->deleteImage(public_path(FilePaths::BASE_IMAGES_PATH.$order->item_purchased_receipt));
//            $imageExists = Image::where("name", $order->item_purchased_receipt)->first();
//            if($imageExists){
//                $this->ImageService->deleteImage(public_path(FilePaths::BASE_IMAGES_PATH.$imageExists->name));
//                if($imageExists->imageOrder){
//                    $imageExists->imageOrder->delete();
//                }
//                $imageExists->delete();
//            }
        }

//        $locations = Order::saveOrderImages([$request->file('receipt')], $order->id, "receipt");

        $order->item_purchased_receipt = $this->saveImage($request->file('receipt'));
        $order->item_purchased_amount  = $request->amount;
        $order->status  = OrderStatus::PURCHASED;

        $order->save();

        Order::sendNotification($order, $order->user_id, 'purchase');

        return $this->sendResponse(['Receipt upload successfully'], 'Receipt Upload Success');
    }

    /**
     * Upload Custom Duty Charges Receipt
     *
     * @bodyParam receipt image required A valid receipt image
     * @bodyParam amount integer required Amount value
     * @bodyParam order_id integer required Order ID
     *
     * @authenticated
     * @response {
     *  "success": true,
     *  "message": "Receipt upload Success",
     *  "data"   : ["Receipt upload successfully"]
     * }
     * @param Request $request
     * @return Response
     */
    public function uploadCustomDutyChargesReceipt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receipt'   =>      'required|image',
            'amount'    =>      'required|numeric|gt:0',
            'order_id'  =>      'required|integer'
        ], [
            'receipt.required'  =>  'Receipt is required',
            'receipt.image'     =>  'Receipt should be a valid image',
            'amount.required'   =>  'Amount is required',
            'amount.integer'    =>  'Amount value is not valid',
            'order_id.required' =>  'Order.ID is required',
            'order_id.integer'  =>  'Order.ID value is not valid'
        ]);


        if ($validator->fails()) {
            return $this->sendError(['Upload Receipt Error'], $validator->errors()->all(), 422);
        }

        $order = Order::findOrFail($request->order_id);

        if($order->traveler_id != auth()->id()){
            return $this->sendError('You are not allowed to do this action', ["Not Authorized"], 422);
        }

        if($order->pin_code){
            return $this->sendError('Item Custom Duty Receipt Error', ["Can't update custom receipt, item handed over initiated"], 422);
        }

        if($order->custom_duty_charges_receipt){
            $this->ImageService->deleteImage(public_path(FilePaths::BASE_IMAGES_PATH.$order->item_purchased_receipt));
//            $imageExists = Image::where("name", $order->custom_duty_charges_receipt)->first();
//            if($imageExists){
//                $this->ImageService->deleteImage(public_path(FilePaths::BASE_IMAGES_PATH.$imageExists->name));
//                if($imageExists->imageOrder){
//                    $imageExists->imageOrder->delete();
//                }
//                $imageExists->delete();
//            }
        }

//        $locations = Order::saveOrderImages([$request->file('receipt')], $order->id, "custom_duty");

        $order->custom_duty_charges_receipt = $this->saveImage($request->file('receipt'));
        $order->custom_duty_charges_amount  = $request->amount;
        $order->status  = OrderStatus::CUSTOM_PAID;

        $order->save();

        return $this->sendResponse(['Receipt upload successfully'], 'Receipt Upload Success');
    }

    private function saveImage($image)
    {
        $originalName = $image->getClientOriginalName();

        $uniqueName = Helper::getUniqueImageName($originalName);
        $fileName   = $originalName;

        Image::create([
            'original_name' => $fileName,
            'name'          => $uniqueName,
            'uploaded_by'   => auth()->id()
        ]);

        $image->move(public_path(FilePaths::BASE_IMAGES_PATH), $uniqueName );

        return $uniqueName;
    }

    public function generateSecurityCode($id)
    {
        $order = Order::findOrFail($id);

        if($order->status == OrderStatus::COMPLETED){
            return $this->sendError('Order Already Completed', ["Not Allowed"], 422);
        }

        if($order->traveler_id != auth()->id()){
            return $this->sendError('You are not allowed to do this action', ["Not Authorized"], 422);
        }

        $order->update([
            "pin_code" => substr(number_format(time() * rand(),0,'',''),0,6),
            "pin_time_to_live"=> now()->addMinutes(5),
            "status" => OrderStatus::CODE_GENERATED
        ]);

        Order::sendNotification($order, $order->user_id, 'code_generate');

        return $this->sendResponse(['Security Code Generated'], 'Ask Customer For Security Code');
    }

    public function fetchSecurityCode($id)
    {
        $order = Order::findOrFail($id);

        if($order->status == OrderStatus::COMPLETED){
            return $this->sendError('Order Already Completed', ["Not Allowed"], 422);
        }

        if($order->user_id != auth()->id()){
            return $this->sendError('You are not allowed to do this action', ["Not Authorized"], 422);
        }

        return $this->sendResponse(["pin_code" => $order->pin_code], 'Here is your pin code');
    }


    public function itemHandedOver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'      =>      'required',
            'order_id'  =>      'required|integer'
        ], [
            'code.required'     =>  'Code is required',
            'order_id.required' =>  'Order.ID is required',
            'order_id.integer'  =>  'Order.ID value is not valid'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Upload Receipt Error', $validator->errors()->all(), 422);
        }

        $order = Order::findOrFail($request->order_id);

        if($order->status != OrderStatus::CODE_GENERATED){
            return $this->sendError('Code Is Not Generated For This Order', ["Order is in (" . $order->status . ") status"], 422);
        }

        if($order->traveler_id != auth()->id()){
            return $this->sendError('You Are Not Allowed To Perform This Action', ["Not Authorized"], 422);
        }

        if(now() > $order->pin_time_to_live){
            return $this->sendError('Code Expired', ["Re-Generate Code Again. Ask Customer Again "], 422);
        }

        if($request->code != $order->pin_code){
            return $this->sendError('Invalid Code', ["Please Try Again. Invalid Code"], 422);
        }

        $order->update([
            "pin_code" => null,
            "pin_time_to_live"=> null,
            "status" => OrderStatus::HANDED
        ]);

        $offer = $order->payment->offer?: $order->payment->counterOffer->offer;
        $offer->chatRoom()->update([
            'is_active' => false
        ]);

        return $this->sendResponse(['Handed Over Successfully'], 'Item Handed Over To Customer');
    }

    public function travelerReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "customer_rating"   => 'required|integer',
            "customer_review"   => 'required|string',
            'order_id'          =>  'required|integer'
        ], [
            'customer_rating.required'      =>  'Rating is required',
            'customer_rating.integer'       =>  'Rating value must be integer',
            'customer_review.required'      =>  'Review is required',
            'customer_review.string'        =>  'Review value must be string',
            'code.required'                 =>  'Code is required',
            'order_id.required'             =>  'Order.ID is required',
            'order_id.integer'              =>  'Order.ID value is not valid'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Customer Review Error', $validator->errors()->all(), 422);
        }

        $order = Order::findOrFail($request->order_id);

        if($order->traveler_id != auth()->id()){
            return $this->sendError('Not Authorized', ["You Are Not Allowed To Perform This Action"], 422);
        }

        if($order->customer_rating){
            return $this->sendError("Not Allowed", ["Already Review Submitted"], 422);
        }

        $order->update([
            "customer_rating" => $request->customer_rating,
            "customer_review" => $request->customer_review,
            "status"          => $order->status == OrderStatus::TRAVELER_RATED? OrderStatus::RATED : OrderStatus::CUSTOMER_RATED
        ]);

        Order::sendNotification($order, $order->user_id, 'review');

//        if ($order->status == OrderStatus::RATED){
//            $offer = $order->payment->offer?: $order->payment->counterOffer->offer;
//            $offer->chatRoom()->update([
//                'is_active' => false
//            ]);
//        }

        return $this->sendResponse(['Review Submitted Successfully'], 'Thank You For Submitting Review');
    }

    public function clientReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "traveler_rating"   => 'required|integer',
            "traveler_review"   => 'required|string',
            'order_id'          =>  'required|integer'
        ], [
            'traveler_rating.required'      =>  'Rating is required',
            'traveler_rating.integer'       =>  'Rating value must be integer',
            'traveler_review.required'      =>  'Review is required',
            'traveler_review.string'        =>  'Review value must be string',
            'code.required'                 =>  'Code is required',
            'order_id.required'             =>  'Order.ID is required',
            'order_id.integer'              =>  'Order.ID value is not valid'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Traveler Review Error', $validator->errors()->all(), 422);
        }

        $order = Order::findOrFail($request->order_id);

        if($order->user_id != auth()->id()){
            return $this->sendError('Not Authorized', ["You Are Not Allowed To Perform This Action"], 422);
        }

        if($order->traveler_rating){
            return $this->sendError("Not Allowed", ["Already Review Submitted"], 422);
        }

        $order->update([
            "traveler_rating" => $request->traveler_rating,
            "traveler_review" => $request->traveler_review,
            "status"          => $order->status == OrderStatus::CUSTOMER_RATED? OrderStatus::RATED : OrderStatus::TRAVELER_RATED
        ]);

        Order::sendNotification($order, $order->traveler_id, 'review');

//        if ($order->status == OrderStatus::RATED){
//            $offer = $order->payment->offer?: $order->payment->counterOffer->offer;
//            $offer->chatRoom()->update([
//                'is_active' => false
//            ]);
//        }

        return $this->sendResponse(['Review Submitted Successfully'], 'Thank You For Submitting Review');
    }
}
