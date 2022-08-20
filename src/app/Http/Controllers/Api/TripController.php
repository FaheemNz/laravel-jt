<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Trip;
use App\Order;
use App\Utills\Constants\OrderStatus;
use App\Http\Resources\HomeOrderResource;
use App\Http\Resources\TravelerOrderResource;
use App\Http\Resources\TravelerTripResource;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @group Trip
 *
 */
class TripController extends BaseController
{
    /**
     *
     * View all Trips
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *       "data": [
     *
     *       ],
     *       "links": {
     *           "first": "http://brrring.polt.pk/api/v1/trips?page=1",
     *           "last": "http://brrring.polt.pk/api/v1/trips?page=1",
     *           "prev": null,
     *           "next": null
     *       },
     *       "meta": {
     *           "current_page": 1,
     *           "from": 1,
     *         "last_page": 1,
     *           "path": "http://brrring.polt.pk/api/v1/trips",
     *           "per_page": 10,
     *           "to": 3,
     *           "total": 3
     *       }
     *   },
     *   "message": "Trips retrieved successfully"
     * }
     */
    public function index()
    {
        #$this->updateTripsStatus();
        $query = Trip::where('user_id', auth()->id());

        $status = request()->input('status');

        if ($status) {
            $query->where('status', $status);
        }

        return $this->sendResponse(
            TravelerTripResource::collection(
                $query->latest()->paginate(3)
            )
                ->response()
                ->getData(true),
            'Trips retrieved successfully'
        );
    }


    /**
     *
     * Store a new Trip
     *
     * @bodyParam arrival_date string required Arrival Date of the Trip
     * @bodyParam from_city_id integer required The ID of from City
     * @bodyParam destination_city_id integer required The ID of destination city
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *       "id": 16,
     *       "arrival_date": "1970-01-01 00:00:00",
     *       "status": "active",
     *       "from_city_id": "2",
     *       "from_city": {
     *           "id": 2,
     *           "name": "Garacharma",
     *           "state": "Andaman and Nicobar Islands",
     *           "country": "",
     *           "flag_url": ""
     *       },
     *       "destination_city_id": null,
     *       "destination_city": {
     *           "id": 1,
     *           "name": "Bombuflat",
     *           "state": "Andaman and Nicobar Islands",
     *           "country": "",
     *           "flag_url": ""
     *       },
     *       "completeSourceAddress": "Garacharma",
     *       "completeDestinationAddress": "Bombuflat",
     *       "totalOffer": 0,
     *       "accepterOffers": 0,
     *       "disputedOffers": 0
     *   },
     *   "message": "Trip created successfully"
     * }
     *
     * @response 422
     * {
     *   "success": false,
     *   "message": [
     *       "The from city id field is required."
     *   ],
     *   "data": "Validation failed"
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_city_id'          => 'required|integer|exists:cities,id',
            'destination_city_id'   => 'required|integer|exists:cities,id|different:from_city_id',
            'arrival_date'          => 'date|required|date_format:Y-m-d'
        ], [
            'from_city_id.exists'           =>      'From.City.ID does not exist in our system',
            'destination_city_id.exists'    =>      'Destination.City.ID does not exist in our system',
            'destination_city_id.different' =>      'Destination City must be different from From City',
            'destination_city_id.required'  =>      'Destination.City.ID is required',
            'arrival_date.date'             =>      'Arrival date is not valid',
            'arrival_date.date_format'      =>      'Arrival date format is not in valid format',
            'arrival_date.required'         =>      'Arrival date is required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors()->all());
        }

        $trip = Trip::create([
            'from_city_id'          => $request->from_city_id,
            'destination_city_id'   => $request->destination_city_id,
            'arrival_date'          => $request->arrival_date,
            'user_id'               => auth()->id(),
            'status'                => 'active'
        ]);

        return $this->sendResponse(
            new TravelerTripResource($trip),
            'Trip created successfully'
        );
    }

    /**
     *
     * Update the specified trip.
     *
     * @urlParam id required ID of the trip
     *
     * @bodyParam arrival_date string required Arrival Date of the Trip
     * @bodyParam from_city_id integer required The ID of from City
     * @bodyParam destination_city_id integer required The ID of destination city
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *       "id": 16,
     *       "arrival_date": "1970-01-01 00:00:00",
     *       "status": "in_active",
     *       "from_city_id": "2",
     *       "from_city": {
     *           "id": 2,
     *           "name": "Garacharma",
     *           "state": "Andaman and Nicobar Islands",
     *           "country": "",
     *           "flag_url": ""
     *       },
     *       "destination_city_id": null,
     *       "destination_city": {
     *          "id": 1,
     *          "name": "Bombuflat",
     *           "state": "Andaman and Nicobar Islands",
     *          "country": "",
     *           "flag_url": ""
     *       },
     *       "completeSourceAddress": "Garacharma",
     *       "completeDestinationAddress": "Bombuflat",
     *       "totalOffer": 0,
     *       "accepterOffers": 0,
     *       "disputedOffers": 0
     *   },
     *   "message": "Trip updated successfully"
     * }
     */
    public function update(Request $request, $id)
    {
        $trip = Trip::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$trip) {
            return $this->sendError('Trip validation Failed', ['No such trip found']);
        }
        if ($trip->offers->count() > 0) {
            return $this->sendError('Trip validation Failed', ['Update declined because there are offers already existing for this trip']);
        }

        $validator = Validator::make($request->all(), [
            'from_city_id'          => 'required|integer|exists:cities,id',
            'destination_city_id'   => 'required|integer|exists:cities,id|different:from_city_id',
            'arrival_date'          => 'date|required|date_format:Y-m-d'
        ], [
            'from_city_id.exists'           =>      'From.City.ID does not exist in our system',
            'destination_city_id.exists'    =>      'Destination.City.ID does not exist in our system',
            'destination_city_id.different' =>      'Destination City must be different from From City',
            'destination_city_id.required'  =>      'Destination.City.ID is required',
            'arrival_date.date'             =>      'Arrival date is not valid',
            'arrival_date.date_format'      =>      'Arrival date format is not valid',
            'arrival_date.required'         =>      'Arrival date is required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Trip Validation failed', $validator->errors()->all(), 400);
        }

        $trip->update([
            'from_city_id'          => $request->from_city_id,
            'destination_city_id'   => $request->destination_city_id,
            'arrival_date'          => $request->arrival_date
        ]);

        return $this->sendResponse(
            new TravelerTripResource($trip),
            'Trip updated successfully'
        );
    }

    /**
     *
     * Remove the specified trip.
     *
     * @authenticated
     * @urlParam id required ID of the trip
     */
    public function destroy(int $id)
    {
        $trip = Trip::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$trip) {
            return $this->sendError('Trip Error', ['No such trip found'], 404);
        }
        if (($trip->offers->count() > 0)) {
            return $this->sendError('Trip Error', ['Cant delete Trip now as there are offers active for this trip']);
        }

        $trip->delete();

        return $this->sendResponse(
            new TravelerTripResource($trip),
            'Trip deleted successfully'
        );
    }

    /**
     *
     * Change status of Trip
     *
     * @urlParam id required ID of the trip
     * @bodyParam status string required Must be either active, in_active, or completed
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *       "id": 16,
     *       "arrival_date": "1970-01-01",
     *       "status": "active",
     *       "from_city_id": 2,
     *       "from_city": {
     *           "id": 2,
     *           "name": "Garacharma",
     *           "state": "Andaman and Nicobar Islands",
     *           "country": "",
     *           "flag_url": ""
     *       },
     *       "destination_city_id": null,
     *       "destination_city": {
     *           "id": 1,
     *     "name": "Bombuflat",
     *           "state": "Andaman and Nicobar Islands",
     *           "country": "",
     *           "flag_url": ""
     *       },
     *       "completeSourceAddress": "Garacharma",
     *       "completeDestinationAddress": "Bombuflat",
     *       "totalOffer": 0,
     *       "accepterOffers": 0,
     *       "disputedOffers": 0
     *   },
     *   "message": "Trip status changed successfully"
     * }
     *
     * @response 422
     * {
     *   "success": false,
     *   "message": [
     *       "The status field is required."
     *   ],
     *   "data": "Validation failed"
     * }
     */
    public function changeStatus(Request $request, int $id)
    {
        $trip = Trip::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$trip) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'No such trip found');

            throw new ValidationException($validator);
        }
        if (($trip->offers->count() > 0)) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'Cant change offer status');

            throw new ValidationException($validator);
        }

        $validator = Validator::make($request->all(), [
            'status'    => 'required|in:active,in_active,completed'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors()->all(), 422);
        }

        $trip->status = $request->status;

        return $this->sendResponse(
            new TravelerTripResource($trip),
            'Trip status changed successfully'
        );
    }

    /**
     *
     * Display Orders for a Specific Trip
     *
     * @urlParam id required ID of the trip
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "data": {
     *       "data": [],
     *       "links": {
     *           "first": "http://brrring.polt.pk/api/v1/trips/16/orders?page=1",
     *           "last": "http://brrring.polt.pk/api/v1/trips/16/orders?page=1",
     *           "prev": null,
     *           "next": null
     *       },
     *       "meta": {
     *           "current_page": 1,
     *           "from": null,
     *           "last_page": 1,
     *           "path": "http://brrring.polt.pk/api/v1/trips/16/orders",
     *           "per_page": 10,
     *           "to": null,
     *           "total": 0
     *       }
     *   },
     *   "message": "Get trip orders successfully"
     * }
     */
    public function getTripOrders(int $id)
    {
        $trip = Trip::findOrFail($id);

        if (!$trip) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'No such trip found');
            throw new ValidationException($validator);
        }
        if ($trip->user_id != Auth::user()->id) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'Unauthorized for this trip');
            throw new ValidationException($validator);
        }

        //?? =============== Get Predefined Query Values ====================
        $status = request()->input('status');
        if ($status) {
            if ($trip->offers->count() <= 0) {
                //?? Need to find a better solution
                $query = Order::where('id', -1)->whereDate('needed_by', '>', date("Y-m-d"));
            } else {
                $order_ids = $trip->offers->pluck('order_id');
                $query = Order::whereIn('id', $order_ids)
                    ->where('from_city_id', $trip->from_city_id)
                    ->where('destination_city_id', $trip->destination_city_id)
                    ->whereDate('needed_by', '>', date('Y-m-d', strtotime($trip->arrival_time)));

                if ($status == 'in_transit') {
                    $status_array = OrderStatus::IN_TRANSIT;
                    $query =  $query->where("traveler_id", auth()->id());

                } elseif ($status == 'completed') {
                    $status_array = OrderStatus::DONE;
                } else {
                    $validator = Validator::make([], []);
                    $validator->errors()->add('error', 'No such status exist');
                    throw new ValidationException($validator);
                }
                $query->whereIn('status', $status_array);
            }

            return $this->sendResponse(
                TravelerOrderResource::collection($query->latest()->paginate(request()->get('perPage', 10)))
                    ->response()->getData(true),
                'Get trip orders successfully'
            );
        } else {
            // $query = Order::with('images')->where(function($q) use ($trip){
            //     $q->whereDate('needed_by', '>=', date('Y-m-d',strtotime($trip->arrival_time)))
            //     ->orWhereNull('needed_by');
            // })->where('destination_city_id',$trip->destination_city_id)->where('status','new')->where('user_id','!=',Auth::user()->id);
            $query = Order::with('images')
                ->where('from_city_id', $trip->from_city_id)
                ->where('destination_city_id', $trip->destination_city_id)
                ->whereDate('needed_by', '>', date('Y-m-d', strtotime($trip->arrival_time)))
                ->whereIn('status', [OrderStatus::NEW, OrderStatus::PAYMENT_IN_PROGRESS])
                ->where('user_id', '!=', Auth::user()->id);
            return $this->sendResponse(
                HomeOrderResource::collection($query->latest()->paginate(request()->get('perPage', 10)))
                    ->response()->getData(true),
                'Get trip orders successfully'
            );
        }
    }

    /**
     * Update trips and set status to inactive if offers and trip is expired.
     *
     * @authenticated
     */
    private static function updateTripsStatus()
    {
        $trips = Trip::with('offers')->where('user_id', auth()->user()->id)->where('status', 'active')->get();
        foreach ($trips as $trip) {

            $now = Carbon::now();
            $arrival_date = Carbon::createFromFormat('Y-m-d', $trip->arrival_date);

            // if arrival date has passed
            if ($now->gt($arrival_date)) {
                //check if there are existing offers
                $offers = $trip->offers;
                if (count($offers)) {
                    // check if any of the offers are accepted
                    // $accepted_offers = Offer::with('order')->where('trip_id', $trip->id)->where('status', 'accepted')->get();
                    $accepted_offers = $trip->offers()->with('order')->where('trip_id', $trip->id)->where('status', 'accepted')->get();
                    if (count($accepted_offers)) {
                        // check if the orders of these offers have been traveler_paid
                        foreach ($accepted_offers as $accepted_offer) {
                            $order = $accepted_offer->order;
                            if ($order->status == 'traveler_paid') {
                                // expire the trip
                                $trip->status = 'in_active';
                                $trip->update([]);
                            } else {
                                // let the trip be there
                            }
                        }
                    } else {
                        // no offers accepted for this trip
                        // expire this trip
                        $trip->status = 'in_active';
                        $trip->update([]);
                    }
                } else {
                    // expire this trip
                    $trip->status = 'in_active';
                    $trip->update([]);
                }
            }
        }
    }
}
