<?php

namespace App\Observers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\FCMController;
use App\Offer;
use App\Notification;
use Exception;
use App\Http\Resources\HomeOrderResource;
use App\Http\Resources\TravelerOrderResource;

class OfferObserver
{
    /**
     * Handle the offer "created" event.
     *
     * @param  \App\Offer  $offer
     * @return void
     */
    public function created(Offer $offer)
    {
        try {
            $deviceTokens = [];
            $customer   = $offer->order->createdBy;
            $customerToken = $customer->device_token;
            if($offer->status == 'unaccepted') {
                ($customerToken) ? array_push($deviceTokens,$customerToken) : '';
                $FCMController = new FCMController();
                $requestBody = [
                    'device_tokens'  => $deviceTokens,
                    'title'          => 'New Offer On Your Order',
                    'body'           => 'New Offer placed on your order '. ucwords($offer->order->name),
                    'data'           => [
                        'entity_type' => 'ItemDetails',
                        'entity'      => new HomeOrderResource($offer->order)
                    ]
                ];
                $requestBody = new Request($requestBody);
                $responseFCM = $FCMController->sendNotification($requestBody);
                \Log::info('============= FCM Raw Response Offer Created ===============');
                \Log::info(json_encode($responseFCM));

                // Notification for Customer
                $customer_notification = Notification::create([
                    'title' => $requestBody["title"],
                    'description' => $requestBody["body"],
                    'user_id' => $customer->id,
                    'type' => 'offer',
                    'body' => json_encode($requestBody['data']),
                ]);
                \Log::info('============= Notification (Customer) ===============');
                \Log::info(json_encode($customer_notification));
            }
        } catch (Exception $error) {
            \Log::info(json_encode($error));
        }
    }

    /**
     * Handle the offer "updated" event.
     *
     * @param  \App\Offer  $offer
     * @return void
     */
    public function updated(Offer $offer)
    {
        try {
            $deviceTokens = [];
            $traveler   = $offer->createdBy;
            $travelerToken = $traveler->device_token;

            if($offer->status == 'accepted') {
                ($travelerToken) ? array_push($deviceTokens,$travelerToken) : '';
                $FCMController = new FCMController();
                $requestBody = [
                    'device_tokens' => $deviceTokens,
                    'title'          => 'Offer Accepted',
                    'body'           => 'Your offer for order '. ucwords($offer->order->name) . ' accepted',
                    'data'           => [
                        'entity_type' => 'InTransitTripDetails',
                        'entity'      => new TravelerOrderResource($offer->order)
                    ]
                ];
                $requestBody = new Request($requestBody);
                $responseFCM = $FCMController->sendNotification($requestBody);
                \Log::info('============= FCM Raw Response Offer Updated===============');
                \Log::info(json_encode($responseFCM));

                // Notification for Traveler
                $traveler_notification = Notification::create([
                    'title' => $requestBody["title"],
                    'description' => $requestBody["body"],
                    'user_id' => $traveler->id,
                    'type' => 'offer',
                    'body' => json_encode($requestBody['data']),
                ]);
                \Log::info('============= Notification (Traveler) ===============');
                \Log::info(json_encode($traveler_notification));
            }
        } catch (Exception $error) {
            if(is_object($error)) {
                \Log::info(json_encode($error));
            } else {
                \Log::info($error);
            }
        }
    }

    /**
     * Handle the offer "deleted" event.
     *
     * @param  \App\Offer  $offer
     * @return void
     */
    public function deleted(Offer $offer)
    {
        //
    }

    /**
     * Handle the offer "restored" event.
     *
     * @param  \App\Offer  $offer
     * @return void
     */
    public function restored(Offer $offer)
    {
        //
    }

    /**
     * Handle the offer "force deleted" event.
     *
     * @param  \App\Offer  $offer
     * @return void
     */
    public function forceDeleted(Offer $offer)
    {
        //
    }
}
