<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\SystemSetting;
use App\Utills\Constants\SystemSettingsType;
use Illuminate\Http\Request;

class PoliciesController extends BaseController
{
    public function index(Request $request)
    {
        $policies = SystemSetting::query();
        if(isset($request->key)){
            $policies = $policies->where("key", $request->key);
        }

        $policies_keys = [
            "customer_order_placement_policy",
            "traveller_offer_placement_policy",
            "customer_offer_acceptance_policy",
            "customer_counter_offer_placement_policy",
            "traveller_counter_offer_acceptance_policy",
            "traveller_purchasing_item_policy",
            "traveller_paying_custom_policy",
            "traveller_item_handing_over_policy",
            "customer_item_received_policy",
            "user_raising_dispute_policy",
            "user_registration_policy",
            "user_data_policy"
        ];

        $policies = $policies->where("type", SystemSettingsType::POLICY)
            ->get(["id","key","value","description"]);
        return $this->sendResponse(["policies" => $policies, "possible_keys" => $policies_keys], "List Of Policies", 200);
    }
}
