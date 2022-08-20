<?php

namespace App\Observers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\FCMController;
use App\Order;
use App\Notification;
use Exception;
use App\Http\Resources\TravelerOrderResource;
use App\Http\Resources\CustomerOrderResource;
use Auth;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        try {
            $customerId = $order->user_id;
            $offer = $order->offers()->where('status','accepted')->first();
            $travelerId = ($offer) ? $offer->user_id : null;

            $requestBody = [
                'order_id'              => $order->id,
                'status'                => $order->status,
                'images'                => $order->images()->where('type','customer')->pluck('name'),
                'duty_charges_images'   => $order->images()->where('type','custom_duty')->pluck('name'),
                'purchased_images'      => $order->images()->where('type','receipt')->pluck('name'),
                'customer_id'           => $customerId,
                'traveler_id'           => $travelerId
            ];

            $response = Http::post(env('SOCKET_URL','http://localhost:3000').'/updateOrder', $requestBody);
            \Log::info('============= Socket Raw Response Order Updated ===============');
            \Log::info(json_encode($response));

            $FCMController = new FCMController();


            //===== Traveler =====
            $deviceTokens = [];
            $travelerToken = $offer->createdBy->device_token;
            ($travelerToken) ? array_push($deviceTokens,$travelerToken) : '';
            $requestBody = [
                'device_tokens'  => $deviceTokens,
                'title'          => 'Order Status Updated',
                'body'           => 'Order status have been updated to '. ucwords($order->status),
                'data'           => [
                    'entity_type' => 'InTransitTripDetails',
                    'entity'      => new TravelerOrderResource($order)
                ]
            ];
            $requestBody = new Request($requestBody);
            $responseFCM = $FCMController->sendNotification($requestBody);
            \Log::info('============= FCM Raw Response Order Updated (Traveler) ===============');
            \Log::info(json_encode($responseFCM));

            if($order->status != 'paid') {
                // Notification for Traveler
                $traveler_notification = Notification::create([
                    'title' => $requestBody["title"],
                    'description' => $requestBody["body"],
                    'user_id' => $travelerId,
                    'type' => 'order',
                    'body' => json_encode($requestBody['data']),
                ]);
                \Log::info('============= Notification (Traveler) ===============');
                \Log::info(json_encode($traveler_notification));
            }
            //===== Customer =====
            $deviceTokens = [];
            $customerToken = $offer->order->createdBy->device_token;
            ($customerToken) ? array_push($deviceTokens,$customerToken) : '';
            $requestBody = [
                'device_tokens'  => $deviceTokens,
                'title'          => 'Order Status Updated',
                'body'           => 'Order status have been updated to '. ucwords($order->status),
                'data'           => [
                    'entity_type' => 'InTransitItemDetails',
                    'entity'      => new CustomerOrderResource($order)
                ]
            ];

            $requestBody = new Request($requestBody);

            $responseFCM = $FCMController->sendNotification($requestBody);
            \Log::info('============= FCM Raw Response Order Updated (Customer) ===============');
            \Log::info(json_encode($responseFCM));

            // Notification for Customer
            $cutomer_notification = Notification::create([
                'title' => $requestBody["title"],
                'description' => $requestBody["body"],
                'user_id' => $customerId,
                'type' => 'order',
                'body' => json_encode($requestBody['data']),
            ]);
            \Log::info('============= Notification (Customer) ===============');
            \Log::info(json_encode($cutomer_notification));
            
        } catch (Exception $error) {
            if(is_object($error)) {
                \Log::info(json_encode($error));
            } else {
                \Log::info($error);
            }
        }
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {

    }
}
