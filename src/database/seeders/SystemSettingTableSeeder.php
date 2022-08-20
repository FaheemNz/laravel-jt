<?php

namespace Database\Seeders;

use App\SystemSetting;
use App\Utills\Constants\SystemSettingsType;
use Illuminate\Database\Seeder;

class SystemSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                "id"            => 1,
                "key"           => "order_status_refresh_time",
                "value"         => 15,
                "description"   => "Must be in minutes, This time is used for refresh order status back from Payment In Progress to New or Accepted.",
                "type"          => SystemSettingsType::APP,
                "status"        => 2
            ],
            [
                "id"            => 2,
                "key"           => "traveler_service_charges_percentage",
                "value"         => 3,
                "description"   => "From 1 to 100, This is percentage of service charges system will take from traveller.",
                "type"          => SystemSettingsType::APP,
                "status"        => 2
            ],
            [
                "id"            => 3,
                "key"           => "customer_service_charges_percentage",
                "value"         => 5,
                "description"   => "From 1 to 100, This is percentage of service charges system will take from customer.",
                "type"          => SystemSettingsType::APP,
                "status"        => 2
            ],
            [
                "id"            => 4,
                "key"           => "customer_duty_charges_percentage",
                "value"         => 20,
                "description"   => "From 1 to 100, This is percentage of duty that system in advanced take from customer. Returnable",
                "type"          => SystemSettingsType::APP,
                "status"        => 2
            ],
            [
                "id"            => 5,
                "key"           => "customer_order_placement_policy",
                "value"         => "This is Customer Order Placement Policy",
                "description"   => "This policy will be shown to customer when customer place order",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 6,
                "key"           => "traveller_offer_placement_policy",
                "value"         => "This is Traveller Offer Placement Policy",
                "description"   => "This policy will be shown to traveller when traveller place offer",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 7,
                "key"           => "customer_offer_acceptance_policy",
                "value"         => "This is Customer Offer Acceptance Policy",
                "description"   => "This policy will be shown to customer when customer accept traveller offer",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 8,
                "key"           => "customer_counter_offer_placement_policy",
                "value"         => "This is Customer Counter Offer Placement Policy",
                "description"   => "This policy will be shown to customer when customer make counter offer against traveller offer",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 9,
                "key"           => "traveller_counter_offer_acceptance_policy",
                "value"         => "This is Traveller Counter Offer Acceptance Policy",
                "description"   => "This policy will be shown to traveller when traveller is accept counter offer of customer",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 10,
                "key"           => "traveller_purchasing_item_policy",
                "value"         => "This is Traveller Item Purchasing Policy",
                "description"   => "This policy will be shown to traveller when traveller purchased an item",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 11,
                "key"           => "traveller_paying_custom_policy",
                "value"         => "This is Traveller Custom Paid Policy",
                "description"   => "This policy will be shown to traveller when traveller pays custom on an item",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 12,
                "key"           => "traveller_item_handing_over_policy",
                "value"         => "This is Traveller Item Handed Over Policy",
                "description"   => "This policy will be shown to traveller when traveller hands over item to the custom",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 13,
                "key"           => "customer_item_received_policy",
                "value"         => "This is Customer Item Received Policy",
                "description"   => "This policy will be shown to customer when customer received item from traveller",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 14,
                "key"           => "user_raising_dispute_policy",
                "value"         => "This is Dispute Raising Policy",
                "description"   => "This policy will be shown to user whenever user raise disputes",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 15,
                "key"           => "user_registration_policy",
                "value"         => "This is User Registration Policy",
                "description"   => "This policy will be shown to user when user register",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 16,
                "key"           => "user_data_policy",
                "value"         => "This is User Data Policy",
                "description"   => "This policy will be shown to user when user uploads any data",
                "type"          => SystemSettingsType::POLICY,
                "status"        => 2
            ],
            [
                "id"            => 17,
                "key"           => "nift_link_refresh_time",
                "value"         => 3,
                "description"   => "Must be in minutes, This time is used for refresh NIFT payment link.",
                "type"          => SystemSettingsType::APP,
                "status"        => 2
            ]
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}
