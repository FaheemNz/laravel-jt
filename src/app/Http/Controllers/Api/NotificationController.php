<?php

namespace App\Http\Controllers\Api;

use App\CounterOffer;
use App\Http\Resources\NotificationResource;
use App\Notification;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @group Notification
 * 
 */
class NotificationController extends BaseController
{
    const PER_PAGE = 10;
    
    /**
     * 
     * Get Notifications of the User
     * 
     * @bodyParam only_unread boolean Return only unread notifications 
     * 
     * @authenticated
     * @response
     * {
     *      "success": true,
     *      "data": {
     *          "data": [
     *          {
     *           "id": "ff2b5296-4df8-4960-b5ba-fc85256e3d6e",
     *           "type": "App\\Notifications\\OfferNotification",
     *           "notifiable_type": "App\\User",
     *           "notifiable_id": 1,
     *           "data": {
     *               "id": 9,
     *               "order_id": 1,
     *               "title": "JunaidSwisswinBag",
     *               "description": "You have got a new offer on your order",
     *               "type": "offer",
     *               "order_img": "[\"62654eb3b4d581650806451_b40617db-444b-440f-9281-ad0be623b68b.jpg\"]",
     *               "profile_img": [
     *                   "62654eb3b4d581650806451_b40617db-444b-440f-9281-ad0be623b68b.jpg"
     *               ]
     **           },
     *           "read_at": null,
     *           "created_at": "10 minutes ago",
     *           "updated_at": "10 minutes ago"
     *       }
     *   ],
     *   "links": {
     *       "first": "https://brrring.polt.pk/api/v1/notifications?page=1",
     *       "last": "https://brrring.polt.pk/api/v1/notifications?page=1",
     *       "prev": null,
     *       "next": null
     *   },
     *   "meta": {
     *       "current_page": 1,
     *       "from": 1,
     *       "last_page": 1,
     *       "links": [
     *           {
     *               "url": null,
     *               "label": "&laquo; Previous",
     *               "active": false
     *           },
     *           {
     *               "url": "https://brrring.polt.pk/api/v1/notifications?page=1",
     *               "label": "1",
     *               "active": true
     *           },
     *           {
     *               "url": null,
     *               "label": "Next &raquo;",
     *               "active": false
     *           }
     *       ],
     *       "path": "https://brrring.polt.pk/api/v1/notifications",
     *       "per_page": 10,
     *       "to": 1,
     *       "total": 1
     *   }
     *  },
     * "message": "Notifications retrieved successfully"
     * }
     */
    public function index(Request $request)
    {
        $onlyUnread = $request->only_unread;
        
        $query = Notification::where('notifiable_id', auth()->id())
                ->when($onlyUnread, function($query) {
                    $query->whereNull('read_at');
                })
                ->latest()
                ->paginate(self::PER_PAGE);
                
        return $this->sendResponse(
            NotificationResource::collection($query)
                ->response()
                ->getData(true), 
                'Notifications retrieved successfully'
        );
    }
    
    /**
     * Mark notification as Read
     * 
     * @bodyParam id string required ID of the notification
     * @bodyParam all_as_read boolean Should mark all notifications as read
     * 
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required_without:all_as_read|string|exists:notifications,id',
            'all_as_read' => 'nullable|boolean'
        ], [
            'id.required_without' => 'Please provide a valid ID',
            'all_as_read.boolean' => 'All_As_Read value must be a valid Boolean'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors()->all(), 422);
        }
        
        if($request->all_as_read){
            auth()->user()->unreadNotifications()->update([
                'read_at' => Carbon::now()->toDateTimeString()
            ]);
            
            return $this->sendResponse('Notification Success', 'Notifications have been marked as Read successfully', 200);
        }
        
        Notification::findOrFail($request->id)->update([
            'read_at' => Carbon::now()->toDateTimeString()
        ]);
        
        return $this->sendResponse('Notification Success', 'Notification have been marked as Read successfully', 200);
    }
    
    /**
     * Delete Notifications
     * 
     * @authenticated
     * 
     * 
     */
    public function destroy(Request $request)
    {
        
    }
}
