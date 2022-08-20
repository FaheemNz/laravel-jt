<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Reason;
use App\Utills\Constants\ReasonType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group Reason
 *
 */
class ReasonController extends BaseController
{
    /**
     * Get a List of Reasons for Offer/CounterOffer rejection
     *
     * @response {
     *       "success": true,
     *       "data": [
     *           {
     *               "id": 1,
     *               "description": "Order was late",
     *               "created_at": "2022-04-10T23:32:14.000000Z",
     *               "updated_at": "2022-04-10T23:32:14.000000Z"
     *           },
     *           {
     *               "id": 2,
     *               "description": "Trip was too long",
     *               "created_at": "2022-04-10T23:32:14.000000Z",
     *               "updated_at": "2022-04-10T23:32:14.000000Z"
     *           }
     *       ],
     *       "message": "Reasons Retrieved Successfully"
     * }
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(!$request->type){
            return $this->sendError("Reasons Error", [
                "message" => "Type is missing",
                "possible_types" => ReasonType::ALL
            ], 200);
        }

        $reason_index = array_search($request->type, ReasonType::ALL);
        if(empty(trim($reason_index))){
            return $this->sendError("Reasons Error", [
                "message" => "Type not found",
                "possible_types" => ReasonType::ALL
            ], 200);
        }

        $reasons = Reason::where("type", $reason_index)->get(["id","description","type"]);



        return $this->sendResponse(
            $reasons,
            'Reasons Retrieved Successfully',
            200
        );
    }

    public function reasonTypes()
    {
        return  $this->sendResponse(
            (object) ReasonType::ALL,
            'Reasons Types Retrieved Successfully',
            200
        );
    }
}
