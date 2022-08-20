<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Payment;
use App\Services\Interfaces\PaymentServiceInterface;
use App\SystemSetting;
use App\Utills\Constants\OfferStatus;
use App\Utills\Constants\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Order;
use App\Offer;
use App\User;
use App\Trip;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\CustomerOrderResource;
use App\Http\Resources\TravelerOrderResource;
use App\Http\Resources\HomeOrderResource;
use App\Http\Resources\CustomerOrderDetailResource;
use App\Http\Resources\CustomerOrderOffersResource;
use App\Http\Resources\TravelerOrderOffersResource;
use App\Http\Resources\AcceptOfferPaymentResource;
use App\Http\Resources\TravelerTripResource;
use App\Lib\Helper;
use Illuminate\Validation\ValidationException;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

/**
 * @group Order
 *
 */
class OrderController extends BaseController
{
    protected $PaymentService;
    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->PaymentService = $paymentService;
    }

    /**
     * Display List of Orders
     *
     * @bodyParam status string Status of the Order (in_transit | completed)
     * @bodyParam byMe string Only Display logged in User orders
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *    "id": 98,
     *           "name": "16AprilOrder1",
     *           "description": "Testing counter offer",
     *           "images": [
     *               "625aa6a35098e1650108067_c7290d25-c621-4cfd-bcdb-4b8c3e20116e.jpg"
     *           ],
     *           "image_ids": [
     *               94
     *           ],
     *           "category_id": 4,
     *           "currency_id": 2,
     *           "from_city_id": 1,
     *           "from_city": {
     *               "id": 1,
     *               "name": "Islamabad",
     *               "state": "",
     *               "country": "",
     *               "flag_url": ""
     *           },
     *           "destination_city_id": 8,
     *           "destination_city": {
     *               "id": 8,
     *               "name": "Lahore",
     *               "state": "",
     *               "country": "",
     *               "flag_url": ""
     *           },
     *           "category_name": "Office Supplies",
     *           "category_image_url": "http://localhost/categories/OfficeSupplies.png",
     *           "category_tariff": 20,
     *           "url": null,
     *           "weight": "1",
     *           "quantity": 10,
     *           "price": 2000,
     *           "reward": 50,
     *           "with_box": 1,
     *           "needed_by": "2022-05-31",
     *           "createdBy": {
     *               "id": 24,
     *               "fullName": "Junaid Tahir dot com",
     *               "rating": 0,
     *               "totalCompletedOrders": 0,
     *               "image": []
     *           },
     *           "completeSourceAddress": "Islamabad",
     *           "completeDestinationAddress": "Lahore",
     *           "totalOffers": 2,
     *           "basePrice": 2000,
     *           "basePriceCurrency": "$",
     *           "otherPrice": 357400,
     *           "otherPriceCurrency": "PKR",
     *           "baseRewardPrice": 50,
     *           "baseRewardPriceCurrency": "$",
     *           "otherRewardPrice": 8935,
     *           "otherRewardPriceCurrency": "PKR",
     *           "is_doorstep_delivery": 1,
     *           "is_my_order": false,
     *           "currency": {
     *               "id": 2,
     *               "name": "United States Dollar",
     *               "short_code": "USD",
     *               "symbol": "$",
     *               "rate": 1,
     *               "country_id": 1,
     *               "created_at": "2022-03-11T17:41:17.000000Z",
     *               "updated_at": "2022-03-11T17:41:17.000000Z"
     *           },
     *           "has_counter_offer": "true",
     *           "can_revise": false
     *   },
     *   "message": "Orders retrieved successfully"
     * }
     */
    public function index(Request $request)
    {
        $query = Order::with('category', 'currency', 'createdBy', 'imageOrder.image');

        //Get Predefined Query Values
        $status     = $request->input('status');
        $traveler   = $request->input('traveler');
        $byMe       = $request->input('by');
        $category   = $request->input('category');

        if($category){
            $query = $query->where("category_id", $category);
        }

        $query->where('needed_by', '>', Carbon::now()->format('Y-m-d'));

        // Add Status Filter If Exist
        if ($status) {
            $status_array = [];

            if ($status == 'pending') {
                $status_array = [OrderStatus::NEW, OrderStatus::PAYMENT_IN_PROGRESS];
            } elseif ($status == 'in_transit') {
                    $status_array = OrderStatus::IN_TRANSIT;
            } elseif ($status == 'completed') {
                $status_array = OrderStatus::DONE;
            }

            $query->whereIn('orders.status', $status_array);
        }

        if ($byMe == 'me') {
            $query->where('orders.user_id', auth()->id());

            return $this->sendResponse(
                CustomerOrderResource::collection(
                    $query->latest()->paginate(10)
                )
                    ->response()
                    ->getData(true),
                'Orders retrieved successfully'
            );
        }

        $query = $query
            ->leftJoin('offers', function ($join) {
                $join->on('offers.order_id', '=', 'orders.id');
                $join->on('offers.user_id', '=', DB::raw(auth()->id()));
            })
            ->leftJoin('counter_offer', function ($join) {
                $join->on('counter_offer.offer_id', '=', 'offers.id');
            })
            ->where('orders.user_id', '!=', auth()->id())
            ->select(
                DB::raw("orders.*, CASE WHEN offers.status = 'closed' THEN 1 ELSE 0 END AS has_counter_offer")
            )
            ->orderBy('orders.created_at', 'DESC')
            ->groupBy('orders.id');


        return $this->sendResponse(
            HomeOrderResource::collection(
                $query->paginate(10)
            )
                ->response()
                ->getData(true),
            'Orders retrieved successfully'
        );
    }

    /**
     *
     * Create New Order
     *
     * @bodyParam name string required Name
     * @bodyParam description string required Description
     * @bodyParam category_id string required Category_ID
     * @bodyParam currency_id string required Currency_ID
     * @bodyParam weight string required Weight
     * @bodyParam price integer required Order Price
     * @bodyParam reward string required Order Reward
     * @bodyParam with_box Boolean required
     * @bodyParam needed_by string required Needed By Name
     * @bodyParam destination_city_id integer required ID of the Destination City
     * @bodyParam from_city_id integer required ID of the From City
     * @bodyParam is_doorstep_delivery Boolean(Y/N) required Whether the order is doorstep delivery
     * @bodyParam url string A valid Url
     * @bodyParam images string A valid array containing valid images
     *
     * @authenticated
     * @response {
     *   "success": true,
     *   "data": {
     *       "id": 2,
     *       "name": "Order Name 1",
     *       "description": "Order Description",
     *       "images": [
     *           "/order_offer_images/1.jpg",
     *           "/order_offer_images/3.jpg"
     *       ],
     *       "image_ids": [
     *           1,
     *           3
     *       ],
     *       "duty_charges_images": [],
     *       "purchased_images": [],
     *       "category_name": "Home & Garden",
     *       "category_image_url": "http://localhost/categories/Home&Garden.png",
     *       "category_tariff": 20,
     *       "url": null,
     *       "weight": "11",
     *       "quantity": "1",
     *       "price": 50,
     *       "reward": "12",
     *       "with_box": "1",
     *       "needed_by": "2022-02-02",
     *       "status": "new",
     *       "createdBy": {
     *           "id": 4,
     *           "fullName": "John Doe",
     *           "rating": 0,
     *           "totalCompletedOrders": 0,
     *           "image": {}
     *       },
     *       "customer_rating": null,
     *       "customer_review": null,
     *       "traveler_rating": null,
     *       "traveler_review": null,
     *       "is_disputed": null,
     *       "completeSourceAddress": "",
     *       "completeDestinationAddress": "Bombuflat",
     *       "totalOffers": 0,
     *       "basePrice": 50,
     *       "basePriceCurrency": "$",
     *       "otherPrice": 50,
     *       "otherPriceCurrency": "$",
     *       "baseRewardPrice": "12",
     *       "baseRewardPriceCurrency": "$",
     *       "otherRewardPrice": 12,
     *       "otherRewardPriceCurrency": "$",
     *       "pin_code": null,
     *       "pin_time_to_live": null,
     *       "is_doorstep_delivery": true
     *   },
     *   "message": "Order created successfully"
     * }
     */
    public function store(Request $request, OrderRequest $orderRequest)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();

            $duplicateOrderCheck = DB::select(
                "SELECT COUNT(*) AS is_duplicate
             FROM orders
             WHERE user_id = {$user->id} AND name = '{$orderRequest['name']}'"
            );

            if ($duplicateOrderCheck[0]->is_duplicate > 0) {
                return $this->sendError('Duplicate Order', ['An order with the same name already exists, please try with different name'], 400);
            }

            $fileName = null;
            if($request->hasFile("thumbnail")){
                $image = $orderRequest['thumbnail'];
                $uniqueName = uniqid() .'_'. time() . '_order_thumbnail.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $uniqueName);
                $fileName   = $uniqueName;
            }

            $selectedCategory = Category::find($orderRequest['category_id']);
            $settings = SystemSetting::all();
            $traveler_service_charges_percentage   = $settings->where("key","traveler_service_charges_percentage")->first()->value / 100;
            $customer_service_charges_percentage   = $settings->where("key","customer_service_charges_percentage")->first()->value / 100;
            if ($selectedCategory){
                $customer_duty_charges_percentage      = $selectedCategory->tariff / 100;
            }else{
                $customer_duty_charges_percentage      = $settings->where("key","customer_duty_charges_percentage")->first()->value / 100;
            }

            $order = Order::create([
                'thumbnail'                                 =>  $fileName,
                'name'                                      =>  $orderRequest['name'],
                'description'                               =>  $orderRequest['description'],
                'category_id'                               =>  $orderRequest['category_id'],
                'url'                                       =>  $orderRequest['url'],
                'weight'                                    =>  $orderRequest['weight'],
                'quantity'                                  =>  $orderRequest['quantity'],
                'price'                                     =>  $orderRequest['price'] * $orderRequest['quantity'],
                'currency_id'                               =>  $orderRequest['currency_id'],
                'reward'                                    =>  $orderRequest['reward'],
                'with_box'                                  =>  $orderRequest['with_box'],
                'needed_by'                                 =>  $orderRequest['needed_by'] ?? null,
                'from_city_id'                              =>  $orderRequest['from_city_id'],
                'destination_city_id'                       =>  $orderRequest['destination_city_id'],
                'is_doorstep_delivery'                      =>  $orderRequest['is_doorstep_delivery'],
                'status'                                    =>  OrderStatus::NEW,
                'user_id'                                   =>  $user->id,
                'traveler_service_charges_percentage'       =>  $traveler_service_charges_percentage,
                'customer_service_charges_percentage'       =>  $customer_service_charges_percentage,
                'customer_duty_charges_percentage'          =>  $customer_duty_charges_percentage,
            ]);

            // Save Order Images if they exist
            if (isset($orderRequest['images']) && count($orderRequest['images']) > 0) {
                $imagesUploaded = count($orderRequest['images']);

                if ($imagesUploaded < 1 || $imagesUploaded > 5) {
                    return $this->sendError('Order create Error', ['Minimum 1 image and Maximum 5 images must be uploaded']);
                }

                $locations = Order::saveOrderImages($orderRequest['images'], $order->id);

                // Save First Image As Thumbnail If Not Exists
                if(!$order->thumbnail){
                    $order->update(['thumbnail' => $locations[0]]);
                }
            }

            $userTrips = Trip::select('user_id')
                ->where('user_id',"<>",$order->user_id)
                ->where('from_city_id', $order->from_city_id)
                ->where('destination_city_id', $order->destination_city_id)
                ->where('arrival_date','<',$order->needed_by)
                ->get();

            $usersToNotify = [];

            foreach ($userTrips as $userTrip) {
                $usersToNotify[] = $userTrip->user_id;
            }

            Helper::log('### Order - store ###', [
                'order_id'          =>      $order->id,
                'order_user_id'     =>      $order->user_id,
                'users_to_notify'   =>      $usersToNotify
            ]);

             if (count($usersToNotify) > 0) {
                 Order::sendNotification($order, $usersToNotify, 'create');
             }

            DB::commit();

            return $this->sendResponse(
                new CustomerOrderResource($order),
                'Order created successfully',
                201
            );
        } catch (\Exception $exception) {
            Helper::log('### Order - Store ###', Helper::getExceptionInfo($exception));
            DB::rollBack();
            return $this->sendError('Order Create Error', ['Some error occurred while creating order. Please try later', $exception]);
        }
    }

    /**
     *
     * Display a single Order
     *
     * @urlParam id required Id of the Order
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *       "id": 1,
     *       "name": "User 18 Order",
     *       "description": "This iis an Item description",
     *       "images": [
     *           "/order_offer_images/2.jpg",
     *           "/order_offer_images/3.jpg",
     *           "/order_offer_images/3.jpg"
     *       ],
     *       "image_ids": [
     *           2,
     *           3,
     *           3
     *       ],
     *       "duty_charges_images": [],
     *       "purchased_images": [],
     *       "category_name": "Home & Garden",
     *       "category_image_url": "http://localhost/categories/Home&Garden.png",
     *       "category_tariff": 20,
     *       "url": "https://www.sudoware.co",
     *       "weight": "1",
     *       "quantity": 1,
     *       "price": 150,
     *       "reward": 15,
     *       "with_box": 0,
     *       "needed_by": null,
     *       "status": "new",
     *       "createdBy": {
     *           "id": 1,
     *           "fullName": "Admin Admin",
     *           "rating": 0,
     *           "totalCompletedOrders": 0,
     *           "image": {}
     *       },
     *       "customer_rating": null,
     *       "customer_review": null,
     *       "traveler_rating": null,
     *       "traveler_review": null,
     *       "is_disputed": 1,
     *       "completeSourceAddress": "",
     *       "completeDestinationAddress": "",
     *       "totalOffers": 0,
     *       "basePrice": 150,
     *       "basePriceCurrency": "$",
     *       "otherPrice": "",
     *       "otherPriceCurrency": "",
     *       "baseRewardPrice": 15,
     *       "baseRewardPriceCurrency": "$",
     *       "otherRewardPrice": "",
     *       "otherRewardPriceCurrency": "",
     *       "pin_code": null,
     *       "pin_time_to_live": null,
     *       "is_doorstep_delivery": 0
     *   },
     *  "message": "Order retrieved successfully"
     * }
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        if($order->user_id != auth()->id() && $order->status != OrderStatus::NEW && $order->traveler_id && $order->traveler_id != auth()->id()){
            return $this->sendError("Not Authorized", ["This is not your order."]);
        }

        $order_status_refresh_time = SystemSetting::where("key", "order_status_refresh_time")->first();

//        $order->offers()->where('expiry_date', '>=', date('Y-m-d'))
//        ->update([
//            'status' => OfferStatus::REJECTED
//        ]);

        // If Payment Is Pending And 5 Mins Passes Switch Order Status BACK to NEW
        if($order->status == OrderStatus::PAYMENT_IN_PROGRESS && $order->updated_at->diffInMinutes(now()) > $order_status_refresh_time->value ){
            $order->update(["status" => OrderStatus::NEW]);
            $order->offers()->where("status", OfferStatus::PAYMENT_IN_PROGRESS)->update(['status' => OfferStatus::OPEN]);
            $order->counterOffers()->where("status", OfferStatus::PAYMENT_IN_PROGRESS)->update(['status' => OfferStatus::ACCEPTED]);


            $paymentExists = Payment::where("order_id", $order->id)->where("status", "progress")->first();
            if($paymentExists){
                $paymentExists->delete();
            }
        }

        return $order
            ? $this->sendResponse(new CustomerOrderResource($order), 'Order retrieved successfully')
            : $this->sendError('No order found with the given ID', ['No Order found with the given ID']);
    }

    /**
     *
     * Update the Order
     *
     * @bodyParam name string required Name
     * @bodyParam description string required Description
     * @bodyParam category_id string required Category_ID
     * @bodyParam currency_id string required Currency_ID
     * @bodyParam weight string required Weight
     * @bodyParam price integer required Order Price
     * @bodyParam reward string required Order Reward
     * @bodyParam with_box Boolean required
     * @bodyParam needed_by string required Needed By Name
     * @bodyParam destination_city_id integer required ID of the Destination City
     * @bodyParam is_doorstep_delivery Boolean required Whether the order is doorstep delivery
     * @bodyParam url string A valid Url
     * @bodyParam images array Images Array
     *
     * @authenticated
     * @response {
     *   "success": true,
     *   "data": {
     *       "id": 2,
     *       "name": "Order Name 1",
     *       "description": "Order Description",
     *       "images": [
     *           "/order_offer_images/1.jpg",
     *           "/order_offer_images/3.jpg"
     *       ],
     *       "image_ids": [
     *           1,
     *           3
     *       ],
     *       "duty_charges_images": [],
     *       "purchased_images": [],
     *       "category_name": "Home & Garden",
     *       "category_image_url": "http://localhost/categories/Home&Garden.png",
     *       "category_tariff": 20,
     *       "url": null,
     *       "weight": "11",
     *       "quantity": "1",
     *       "price": 50,
     *       "reward": "12",
     *       "with_box": "1",
     *       "needed_by": "2022-02-02",
     *       "status": "new",
     *       "createdBy": {
     *           "id": 4,
     *           "fullName": "John Doe",
     *           "rating": 0,
     *           "totalCompletedOrders": 0,
     *           "image": {}
     *       },
     *       "customer_rating": null,
     *       "customer_review": null,
     *       "traveler_rating": null,
     *       "traveler_review": null,
     *       "is_disputed": null,
     *       "completeSourceAddress": "",
     *       "completeDestinationAddress": "Bombuflat",
     *       "totalOffers": 0,
     *       "basePrice": 50,
     *       "basePriceCurrency": "$",
     *       "otherPrice": 50,
     *       "otherPriceCurrency": "$",
     *       "baseRewardPrice": "12",
     *       "baseRewardPriceCurrency": "$",
     *       "otherRewardPrice": 12,
     *       "otherRewardPriceCurrency": "$",
     *       "pin_code": null,
     *       "pin_time_to_live": null,
     *       "is_doorstep_delivery": "Y"
     *   },
     *   "message": "Order Updated successfully"
     * }
     */
    public function update(Request $request, int $id, OrderRequest $orderRequest)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$order) {
            return $this->sendError('Order Error', ['No such order found']);
        }
        if ($order->status != 'new' && $order->offers->count() > 0) {
            return $this->sendError('Order Error', ['Cant update Order now']);
        }

        $order->update([
            'name'                  =>      $orderRequest['name'],
            'description'           =>      $orderRequest['description'],
            'category_id'           =>      $orderRequest['category_id'],
            'url'                   =>      $orderRequest['url'],
            'weight'                =>      $orderRequest['weight'],
            'quantity'              =>      $orderRequest['quantity'],
            'price'                 =>      $orderRequest['price'],
            'currency_id'           =>      $orderRequest['currency_id'],
            'reward'                =>      $orderRequest['reward'],
            'with_box'              =>      $orderRequest['with_box'],
            'needed_by'             =>      $orderRequest['needed_by'],
            'from_city_id'          =>      $orderRequest['from_city_id'],
            'destination_city_id'   =>      $orderRequest['destination_city_id'],
            'is_doorstep_delivery'  =>      $orderRequest['is_doorstep_delivery']
        ]);

        return $this->sendResponse(
            new CustomerOrderDetailResource($order),
            'Order updated successfully'
        );
    }

    /**
     *
     * Delete an Order
     *
     * @authenticated
     * @urlParam id required ID of the Order
     */
    public function destroy(int $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$order) {
            return $this->sendError('Order Error', ['No such order found']);
        }

        if ($order->status != 'new' && ($order->offers->count() > 0)) {
            return $this->sendError('Order Error', 'Can delete order now');
        }

        $order->delete();

        return $this->sendResponse(
            new CustomerOrderDetailResource($order),
            'Order deleted successfully'
        );
    }

    /**
     * Get order offers.
     *
     * @urlParam id required ID of the order
     *
     * @authenticated
     * @response
     * {
     *  "success": true,
     * "data": [
     *   {
     *       "id": 7,
     *       "description": "",
     *       "arrival_date": "2022-05-18",
     *       "expiry_date": "2022-05-09",
     *       "badges": [],
     *       "basePrice": 110,
     *       "basePriceCurrency": "$",
     *       "otherPrice": 20465.529999999999,
     *       "otherPriceCurrency": "PKR",
     *       "baseRewardPrice": 5,
     *       "baseRewardPriceCurrency": "$",
     *       "otherRewardPrice": 930.25,
     *       "otherRewardPriceCurrency": "PKR",
     *       "createdBy": {
     *           "id": 3,
     *           "fullName": "Abdul Ahad",
     *           "rating": 0,
     *           "totalCompletedOrders": 0,
     *           "image": {}
     *       },
     *       "status": "closed",
     *       "has_counter_offer": true,
     *       "counter_offer": {
     *           "id": 5,
     *           "description": null,
     *           "status": "accepted",
     *           "currency_id": 2,
     *           "reward": 4,
     *           "expiry_date": "2022-05-26",
     *           "trip_id": 8,
     *           "order_id": 7,
     *           "user_id": 2,
     *           "offer_id": 7,
     *           "is_disabled": 0,
     *           "admin_review": null,
     *           "created_at": "2022-04-23T12:32:48.000000Z",
     *           "updated_at": "2022-04-23T12:33:51.000000Z",
     *           "deleted_at": null,
     *           "reason_id": null,
     *           "counter_traveler_service_charges": 0.12,
     *           "counter_other_traveler_service_charges": 22.329999999999998,
     *           "counter_traveler_earning": 3.8799999999999999,
     *           "counter_other_traveler_earning": 721.88,
     *           "counter_customer_payable": 141.5,
     *           "counter_other_customer_payable": 26326.119999999999,
     *           "other_reward": 744.20000000000005
     *       },
     *       "traveler_service_charges_percentage": 3,
     *       "customer_service_charges_percentage": 5,
     *       "customer_duty_charges_percentage": 20,
     *       "traveler_service_charges": 0.14999999999999999,
     *       "other_traveler_service_charges": 27.91,
     *       "customer_service_charges": 5.5,
     *       "other_customer_service_charges": 1023.28,
     *       "customer_duty_charges": 22,
     *       "other_customer_duty_charges": 4093.1100000000001,
     *       "traveler_earning": 4.8499999999999996,
     *       "other_traveler_earning": 902.34000000000003,
     *       "customer_payable": 142.5,
     *       "other_customer_payable": 26512.169999999998
     *   }
     * ],
     * "message": "Order offers retrieved successfully"
     * }
     */
    public function getOrderOffers(int $id)
    {
        $order = Order::where('id', $id)
            ->first();

        if (!$order) {
            return $this->sendError('Order Offer Error', ['No Such order found'], 404);
        }

        $offers = $order->offers()
            ->where('expiry_date', '>=', date('Y-m-d'))
            ->where('status', '!=', OfferStatus::REJECTED)
            ->get();

        if (count($offers) === 0) {
            return $this->sendError('Order Offer Error', ['No active offers found for this order']);
        }

        if ($order->user_id == auth()->id()) {
            return $this->sendResponse(
                CustomerOrderOffersResource::collection($offers),
                'Order offers retrieved successfully'
            );
        }

        return $this->sendResponse(
            TravelerOrderOffersResource::collection($offers),
            'Order offers retrieved successfully'
        );
    }

    /**
     * Accept offer for specific order.
     *
     * @authenticated
     * @urlParam id required ID of the Order
     * @bodyParam offer_id integer required Id of the offer
     *
     *
     * @response 401 {
     *  "success": false,
     *  "message": ["Accept Offer error', 'You are not authorized to accept this offer"]
     *  "data": []
     * }
     */
    public function acceptOffer(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|integer|exists:offers,id'
        ], [
            'offer_id.required'     =>      'A valid offer id is required',
            'offer_id.integer'      =>      'Offer.Id is not in correct format',
            'offer_id.exists'       =>      'Offer.Id is not valid'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Accept Offer Error', $validator->errors());
        }

        $order = Order::where('id', $id)
            ->whereDate('needed_by', '>=', Carbon::now())
            ->first();

        if (!$order) {
            return $this->sendError('Accept Offer Error', ['No such order found'], 404);
        }
        if ($order->user_id != auth()->id()) {
            return $this->sendError('Accept Offer Error', ['You are not authorized to accept the offer for this order'], 403);
        }
        // if($order->status != 'new' && $order->status != 'tip') {
        //     return $this->sendError('Accept Offer error', 'Cant accept offer, Order already in transit');
        // }
        if ($order->is_disputed) {
            return $this->sendError('Accept Offer Error', ['Cant accept offer, Order is in disputed status']);
        }

        $offer = Offer::where('id', $request->offer_id)
            ->where('order_id', $order->id)
            ->where('status', '!=', OfferStatus::CLOSED)
            ->first();

        if (!$offer) {
            return $this->sendError('Accept Offer Error', ['Cant accept offer, No such offer exist or the offer is not active']);
        }

        $offer->update(["status" => OfferStatus::PAYMENT_IN_PROGRESS]);

        $order->update(["status" => OrderStatus::PAYMENT_IN_PROGRESS]);


        // Create Payment For Order
        $this->PaymentService->createOrderPayment($order);

//        $paymentExists = Payment::where("order_id", $offer->order_id)->first();
//        $new_price      = $offer->price + $offer->reward + ($offer->price * 0.2) + ($offer->price * 0.05);
//        if($paymentExists) {
//            Payment::update([
//                'user_id'       =>      auth()->id(),
//                'amount'        =>      $new_price,
//                'traveler_id'   =>      $offer->user_id,
//            ]);
//        }else{
//            Payment::create([
//                'user_id'       =>      auth()->id(),
//                'order_id'      =>      $order->id,
//                'status'        =>      'progress',
//                'amount'        =>      $new_price,
//                'customer_id'   =>      $order->user_id,
//                'traveler_id'   =>      $offer->user_id,
//                'type'          =>      'credit'
//            ]);
//        }

        Offer::sendNotification(
            $offer,
            $offer->user_id,
            'accept',
            $order->thumbnail,
            $order->name
        );

        return $this->sendResponse(['Offer has been accepted'], 'Success, Offer Accepted');

        // try {
        //     $user_base_currency = auth()->user()->currency()->first();

        //     $offer_price = Helper::convertCurrency(
        //         $offer->currency_id,
        //         $user_base_currency->id,
        //         $offer->price,
        //     );
        //     $offer_reward = Helper::convertCurrency(
        //         $offer->currency_id,
        //         $user_base_currency->id,
        //         $offer->reward,
        //     );

        //     $duty_charges = ($offer_price / 100) * $order->category->tariff; // TODO: multiply by order category charges
        //     $brrring_charges = $offer_price * 0.05;
        //     $total_payable = $offer_price + $offer_reward + $duty_charges + $brrring_charges;

        //     Transaction::create([
        //         'amount'                =>      $total_payable * -1,
        //         'currency_id'           =>      $user_base_currency->id,
        //         'source'                =>      'wallet',
        //         'transaction_details'   =>      json_encode([
        //             'order_id' => $order->id,
        //             'offer_id' => $offer->id,
        //         ]),
        //         'ref_no'                =>      'O' . $order->id . 'T' . time(),
        //         'user_id'               =>      auth()->id(),
        //         'status'                =>      'settled'
        //     ]);

        //     // update offer statuses
        //     Offer::where('id', '!=', $offer->id)
        //         ->where('order_id', $order->id)
        //         ->where('status', '!=', 'rejected')
        //         ->update([
        //             'status' => 'stale'
        //         ]);

        //     $offer->status = 'accepted';
        //     $offer->save();

        //     // change order status to paid
        //     $order->status = 'payment_in_progress';
        //     $order->save();

        //     // create chatroom
        //     ChatRoom::create([
        //         'offer_id'      => $offer->id,
        //         'traveler_id'   => $offer->user_id,
        //         'customer_id'   => $order->user_id
        //     ]);

        //     return $this->sendResponse(
        //         new CustomerOrderOffersResource($offer),
        //         'Order offer accepted successfully'
        //     );

        //     Offer::sendNotification($offer, $offer->user_id, 'accept');
        // } catch (Exception $exception) {
        //     Helper::log('### Order - acceptOffer Exception ###', Helper::getExceptionInfo($exception));

        //     return $this->sendError(
        //         'Accept Offer Exception',
        //         ['Some Error occured while accepting Offer. Please try again later'],
        //         402
        //     );
        // }
    }

    /**
     * Accept offer for specific order.
     *
     * @authenticated
     * @urlParam id required ID of the order
     *
     */
    public function acceptOfferPayment(Request $request, int $id)
    {
        $order = Order::where('id', $id)
            ->whereDate('needed_by', '>=', Carbon::now())
            ->first();

        if (!$order) {
            return $this->sendError('Accept Offer Error', ['No such order found']);
        }
        if ($order->user_id != Auth::user()->id) {
            return $this->sendError('Accept Offer Error', ['You cant accept offer payment for this order']);
        }
        if ($order->status != 'new') {
            return $this->sendError('Accept Offer Error', ['Order already in transit']);
        }
        if( $order->is_disputed ){
            return $this->sendError('Accept Offer Error', ['Cant accept offer, Order is in disputed status']);
        }

        $offer = Offer::where('id', $request->offer_id)
            ->where('order_id', $order->id)
            ->where('is_disabled', false)
            ->first();

        if (!$offer) {
            return $this->sendError('Accept Offer Error', ['Cant accept offer, no such offer exist']);
        }

        try {
            $order->price = $order->offers()->where('status', 'accepted')->first()->price;
            $order->reward = $order->offers()->where('status', 'accepted')->first()->reward;
        } catch (Exception $exception) {
            Log::info("============ Accept Offer - Order Update (exception)===========");
            Log::info($exception);

            return $this->sendError('Accept Offer Error', ['Order Price/Reward Error occured']);
        }

        Log::info("============ Accept Offer - Order Update (order before response)===========");
        Log::info($order);

        return $this->sendResponse(
            new AcceptOfferPaymentResource($order),
            'Order offer accepted successfully'
        );
    }

    /**
     * Customer payment for specific order after accepting offer.
     *
     * @authenticated
     * @response
     * {
     * }
     */
    public function orderPayment(Request $request)
    {
        //Payment process initiated here
        return true;
    }

    /**
     * Update order status.
     *
     * @urlParam id required ID of the Order
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *       "id": 1,
     *       "name": "User 18 Order",
     *       "description": "This iis an Item description",
     *       "images": [
     *           "/order_offer_images/2.jpg",
     *           "/order_offer_images/3.jpg",
     *           "/order_offer_images/3.jpg"
     *       ],
     *       "duty_charges_images": [],
     *       "purchased_images": [],
     *       "category_name": "Home & Garden",
     *       "category_image_url": "http://localhost/categories/Home&Garden.png",
     *       "category_tariff": 20,
     *       "url": "https://www.sudoware.co",
     *       "weight": "1",
     *       "quantity": 1,
     *       "price": 150,
     *       "reward": 15,
     *       "with_box": 0,
     *       "needed_by": null,
     *       "status": "new",
     *       "createdBy": {
     *           "id": 1,
     *           "fullName": "Admin Admin",
     *           "rating": 0,
     *           "totalCompletedOrders": 0,
     *           "image": {}
     *       },
     *      "customer_rating": null,
     *       "customer_review": null,
     *       "traveler_rating": null,
     *       "traveler_review": null,
     *       "is_disputed": 1,
     *       "completeSourceAddress": "",
     *       "completeDestinationAddress": "",
     *       "totalOffers": 13,
     *       "basePrice": 150,
     *       "basePriceCurrency": "$",
     *       "otherPrice": 150,
     *       "otherPriceCurrency": "$",
     *       "baseRewardPrice": 15,
     *       "baseRewardPriceCurrency": "$",
     *       "otherRewardPrice": 15,
     *       "otherRewardPriceCurrency": "$",
     *       "is_doorstep_delivery": 0,
     *       "can_revise": true,
     *       "my_offer": {
     *           "id": 7,
     *           "description": "Duchess! Oh! won't she be savage if I've kept her waiting!' Alice felt a little now and then, 'we.",
     *           "status": "accepted",
     *           "price": 675,
     *           "reward": 8,
     *           "service_charges": null,
     *           "basePriceCurrency": "$",
     *           "trip_id": 4,
     *           "order_id": 1,
     *           "expiry_date": "1991-01-12"
     *       }
     *   },
     *   "message": "Order status updated successfully"
     * }
     */
    public function updateStatus(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'status'    =>  'in:purchased,tracking,handed,received,scanned,traveler_rated,customer_rated,rated,traveler_paid',
            'images'    =>  'required_if:status,purchased,tracking|array|min:1',
            'images.*'  =>  'exists:images,id',
            'id'        =>  'integer|exists:orders,id'
        ], [
            'status.in' => 'Please provide a valid status'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors()->all());
        }

        $order = Order::where('id', $id)
            ->where('is_disabled', false)
            ->first();

        if (!$order) {
            return $this->sendError('Order Error', ['No such order found'], 400);
        }

        if ($order->status == 'new') {
            return $this->sendError('Order Error', ['Unauthorized to update order to this state']);
        }

        $offerCount = $order->offers->count();

        if ($offerCount <= 0) {
            return $this->sendError('Order Error', ['No offers yet for this order']);
        }

        $offer = $order->offers()->where('status', 'accepted')->first();
        if (!$offer) {
            return $this->sendError('Order Error', ['No offer accepted yet for this order']);
        }

        if ($request->status == 'purchased') {
            if ($order->user_id == Auth::user()->id || $offer->user_id != Auth::user()->id) {
                return $this->sendError('Order Error', ['Unauthorized to update order to this state']);
            }
            if ($order->status != 'paid') {
                return $this->sendError('Order Error', ['Cant update order to this state. Please check the current status']);
            }

            // Save order Images if they exist
            Order::saveOrderImages($request->images, $order->id);

            $order->status = $request->status;
            $order->save();
        } else if ($request->status == 'tracking') {
            if ($order->user_id == Auth::user()->id || $offer->user_id != Auth::user()->id) {
                return $this->sendError('Error', ['Unauthorized to update order to this state'], 400);
            }
            if ($order->status != 'purchased') {
                return $this->sendError('Error', ['Cant update order to this state. Please check the current status'], 400);
            }

            // Save order images if they exist
            Order::saveOrderImages($request->images, $order->id);

            $order->status = $request->status;
            $order->save();
        } else if ($request->status == 'handed') {
            if ($order->user_id == Auth::user()->id || $order->user_id != Auth::user()->id) {
                return $this->sendError('Error', ['Unauthorized to update order to this state'], 400);
            }
            if ($order->status != 'purchased' && $order->status != 'tracking') {
                return $this->sendError('Error', ['Can\'t update order to this state. Please check the current status'], 400);
            }
            $order->status = $request->status;
            $order->save();
        } else if ($request->status == 'received') {
            if ($order->user_id != Auth::user()->id) {
                return $this->sendError('Error', ['Unauthorized to update order to this state'], 400);
            }

            if ($order->status != 'handed') {
                return $this->sendError('Error', ['Can\'t update order to this state. Please check the current status'], 400);
            }

            $order->pin_code = mt_rand(100000, 999999);
            $order->pin_time_to_live = Carbon::now()->addMinutes(5);
            $order->status = $request->status;

            $order->save();
        } else if ($request->status == 'scanned') {
            $validator = Validator::make($request->all(), [
                'pin_code'            => 'required|string'
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->all(), 'Validation failed', 400);
            }
            if ($order->user_id == Auth::user()->id) {
                return $this->sendError('Error', ['Unauthorized to update order to this state'], 400);
            }
            if ($offer->user_id != Auth::user()->id) {
                return $this->sendError('Error', ['Unauthorized to update order to this state'], 400);
            }
            if ($order->status != 'received') {
                return $this->sendError('Error', ['Cant update order to this state. Please check the current status'], 400);
            }

            if (!$order->validatePinCode($request->pin_code)) {
                $order->pin_code = mt_rand(100000, 999999);
                $order->pin_time_to_live = Carbon::now()->addMinutes(5);

                $order->save();

                return $this->sendError('Error', ['Not a valid QR code'], 400);
            }

            $order->pin_code = null;
            $order->pin_time_to_live = null;
            $order->status = $request->status;

            $order->save();

            if ($offer->chatRoom) {
                $offer->chatRoom()->update([
                    'is_active' => false
                ]);
            }
        } elseif ($request->status == 'traveler_rated') {
            if ($order->user_id == Auth::user()->id || $offer->user_id != Auth::user()->id) {
                return $this->sendError('Error', ['Unauthorized to update order to this state'], 400);
            }
            if ($order->status != 'rated' && $order->status != "customer_rated" && $order->status != "scanned") {
                return $this->sendError('Error', ['Cant update order to this state. Please check the current status'], 400);
            }

            $validator = Validator::make($request->all(), [
                'traveler_rating'   => 'required|in:1,2,3,4,5',
                'traveler_review'   => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation failed', $validator->errors()->all());
            }

            $order->customer_rating = $request->customer_rating;
            $order->customer_review = $request->customer_review;
            $order->status = $order->status == 'customer_rated' ? 'rated' : $request->status;

            $order->save();

            $this->updateUserRating();
        } elseif ($request->status == 'customer_rated') {

            if ($order->user_id != Auth::user()->id) {
                return $this->sendError('Error', ['Unauthorized to update to this state'], 400);
            }
            if ($order->status != "rated" && $order->status != "traveler_rated" && $order->status != "scanned") {
                return $this->sendError('Error', ['Cant update order to this state. Please check the current status'], 400);
            }

            $validator = Validator::make($request->all(), [
                'customer_rating'   => 'required|in:1,2,3,4,5',
                'customer_review'   => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation failed', $validator->errors()->all(), 400);
            }
            $order->customer_rating = $request->customer_rating;
            $order->customer_review = $request->customer_review;
            $order->status = $order->status == 'traveler_rated' ? 'rated' : $request->status;

            $order->save();

            $this->updateUserRating();
        }

        return $this->sendResponse(
            $order->user_id == Auth::user()->id
                ? new CustomerOrderResource($order)
                : new TravelerOrderResource($order),
            'Order status updated successfully'
        );
    }

    /**
     *
     * Update User rating
     *
     * @authenticated
     */
    public function updateUserRating()
    {

        $userCustomerRating = 0;
        $userTravelRating   = 0;

        $user = User::find(Auth::user()->id);

        $totalOrders         = Order::where('user_id', $user->id)->where('status', '>=', 6)->count();
        $customerRating      = Order::where('user_id', $user->id)->where('status', '>=', 6)->sum('traveler_rating');

        if ($totalOrders > 0) {
            $userCustomerRating = $customerRating / $totalOrders;
        }

        $totalOffers    = Offer::where('user_id', $user->id)->where('status', 'accepted')->count();
        $offers         = Offer::where('user_id', $user->id)->where('status', 'accepted')->pluck('id');
        $travelerRating = Order::whereIn('id', $offers)->sum('customer_rating');

        if ($travelerRating > 0) {
            $userTravelRating = $travelerRating / $totalOffers;
        }

        $user->rating = $userCustomerRating + $userTravelRating;
        $user->save();

        $this->sendResponse('Rating Success', 'User rating has been Updated', 200);
    }

    /**
     *
     * Make an Order Disputed
     *
     * @urlParam id required ID of the Order
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *       "id": 1,
     *       "name": "User 18 Order",
     *       "description": "This iis an Item description",
     *       "images": [
     *           "/order_offer_images/2.jpg",
     *           "/order_offer_images/3.jpg",
     *           "/order_offer_images/3.jpg"
     *       ],
     *       "duty_charges_images": [],
     *       "purchased_images": [],
     *       "category_name": "Home & Garden",
     *       "category_image_url": "http://localhost/categories/Home&Garden.png",
     *       "category_tariff": 20,
     *       "url": "https://www.sudoware.co",
     *       "weight": "1",
     *       "quantity": 1,
     *       "price": 150,
     *       "reward": 15,
     *       "with_box": 0,
     *       "needed_by": null,
     *       "status": "new",
     *       "createdBy": {
     *           "id": 1,
     *           "fullName": "Admin Admin",
     *           "rating": 0,
     *           "totalCompletedOrders": 0,
     *           "image": {}
     *       },
     *       "customer_rating": null,
     *       "customer_review": null,
     *       "traveler_rating": null,
     *       "traveler_review": null,
     *       "is_disputed": true,
     *       "completeSourceAddress": "",
     *       "completeDestinationAddress": "",
     *       "totalOffers": 13,
     *       "basePrice": 150,
     *       "basePriceCurrency": "$",
     *       "otherPrice": 150,
     *       "otherPriceCurrency": "$",
     *       "baseRewardPrice": 15,
     *       "baseRewardPriceCurrency": "$",
     *       "otherRewardPrice": 15,
     *       "otherRewardPriceCurrency": "$",
     *       "is_doorstep_delivery": 0,
     *       "can_revise": true,
     *       "my_offer": {
     *           "id": 7,
     *           "description": "Duchess! Oh! won't she be savage if I've kept her waiting!' Alice felt a little now and then, 'we.",
     *           "status": "accepted",
     *           "price": 675,
     *           "reward": 8,
     *           "service_charges": null,
     *           "basePriceCurrency": "$",
     *           "trip_id": 4,
     *           "order_id": 1,
     *           "expiry_date": "1991-01-12"
     *       }
     *   },
     *   "message": "Order status updated successfully"
     * }
     */
    public function disputedOrder(int $id)
    {
        $order = Order::where('id', $id)
            ->where('is_disabled', false)
            ->first();

        if (!$order) {
            throw new ValidationException(Helper::dummyValidator('No such Order found'));
        }

        $offersCount = $order->offers
            ->where('user_id', Auth::user()->id)
            ->where('status', 'accepted')
            ->count();

        if ((Auth::user()->id == $order->user_id) || ($offersCount > 0)) {
            $order->is_disputed = true;
            $order->save();

            return $this->sendResponse(
                $order->user_id == Auth::user()->id
                    ? new CustomerOrderResource($order)
                    : new TravelerOrderResource($order),
                'Order status updated successfully'
            );
        } else {
            throw new ValidationException(Helper::dummyValidator('Unable to make this order disputed'));
        }
    }

    /**
     *
     * Check Trips for a specific Order
     *
     * @urlParam id required ID of the Order
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *       "data": [
     *           {
     *               "id": 6,
     *               "arrival_date": "2022-02-15",
     *               "status": "active",
     *               "from_city_id": 31594,
     *               "from_city": null,
     *               "destination_city_id": null,
     *               "destination_city": null,
     *               "completeSourceAddress": "Source",
     *               "completeDestinationAddress": "Dest",
     *               "totalOffer": 1,
     *               "accepterOffers": 0,
     *               "disputedOffers": 1
     *           }
     *       ]
     *   },
     *   "message": "Trips exist for this order"
     * }
     */
    public function checkTripsForOrder(int $id)
    {
        $order = Order::where('status', 'new')
            ->where('id', $id)
            ->where('is_disabled', false)
            ->first();

        if (!$order) {
            return $this->sendError("Check Trips Order", ["Order already in process cant make offer now."]);
//            $validator = Validator::make([], []);
//            $validator->errors()->add('error', ['Order already in process cant make offer now.']);
//
//            throw new ValidationException($validator);
        }

        $trips = Trip::where('from_city_id', $order->from_city_id)
            ->where('destination_city_id', $order->destination_city_id)
            ->where('arrival_date', '<', $order->needed_by)
            ->where('arrival_date', '>=', Carbon::now()->toDateString())
            ->where('status', 'active')
            ->where('user_id', Auth::user()->id)
            ->get();

//        return $trips;
        if (count($trips) === 0) {
            return $this->sendError("Check Trips Order", ["No trip available for this order. Please create a trip for further process"]);
//            throw new Exception('No trip available for this order. Please create a trip for further process');
        }

        return $this->sendResponse(
            TravelerTripResource::collection($trips)
                ->response()
                ->getData(true),
            'Trips exist for this order'
        );
    }
}
