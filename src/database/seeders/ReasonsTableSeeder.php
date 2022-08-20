<?php

namespace Database\Seeders;

use App\Reason;
use App\Utills\Constants\ReasonType;
use Illuminate\Database\Seeder;

class ReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            [
                'id'            => 1,
                'type'          => ReasonType::ORDER,
                'description'   => "Ordering illegal item."
            ],
            [
                'id'            => 2,
                'type'          => ReasonType::ORDER,
                'description'   => "Order not showing."
            ],
            [
                'id'            => 3,
                'type'          => ReasonType::TRIP,
                'description'   => "Not able to create trip."
            ],
            [
                'id'            => 4,
                'type'          => ReasonType::TRIP,
                'description'   => "Trip is not match with dates."
            ],
            [
                'id'            => 5,
                'type'          => ReasonType::TRIP,
                'description'   => "City is missing."
            ],
            [
                'id'            => 6,
                'type'          => ReasonType::OFFER,
                'description'   => "Not able to create offer."
            ],
            [
                'id'            => 7,
                'type'          => ReasonType::OFFER,
                'description'   => "Offer is too high."
            ],
            [
                'id'            => 8,
                'type'          => ReasonType::OFFER,
                'description'   => "Found a better offer."
            ],
            [
                'id'            => 9,
                'type'          => ReasonType::COUNTER_OFFER,
                'description'   => "Counter offer is too low."
            ],
            [
                'id'            => 10,
                'type'          => ReasonType::COUNTER_OFFER,
                'description'   => "Not able to create counter offer."
            ],
            [
                'id'            => 11,
                'type'          => ReasonType::GENERAL,
                'description'   => "System Not Responding."
            ],
            [
                'id'            => 12,
                'type'          => ReasonType::USER,
                'description'   => "Cannot Update Password."
            ],
            [
                'id'            => 13,
                'type'          => ReasonType::USER,
                'description'   => "Cannot Update Profile."
            ],
            [
                'id'            => 14,
                'type'          => ReasonType::USER,
                'description'   => "Profile issue."
            ],
            [
                'id'            => 15,
                'type'          => ReasonType::Transaction,
                'description'   => "Not able to create payment."
            ],
            [
                'id'            => 16,
                'type'          => ReasonType::Transaction,
                'description'   => "Did not get payment."
            ],
            [
                'id'            => 17,
                'type'          => ReasonType::SETTINGS,
                'description'   => "Setting messing."
            ],
            [
                'id'            => 18,
                'type'          => ReasonType::APP_USABILITY,
                'description'   => "UI not working properly."
            ],
            [
                'id'            => 19,
                'type'          => ReasonType::APP_USABILITY,
                'description'   => "Field missing."
            ],
            [
                'id'            => 20,
                'type'          => ReasonType::APP_USABILITY,
                'description'   => "Hard to place order and calculate price."
            ],
            [
                'id'            => 21,
                'type'          => ReasonType::OFFER_REJECT,
                'description'   => "Sorry i am rejecting your offer."
            ],
            [
                'id'            => 22,
                'type'          => ReasonType::COUNTER_OFFER_REJECT,
                'description'   => "Sorry i am rejecting your counter offer."
            ]
        ];

        foreach ($reasons as $reason){
            Reason::create($reason);
        }
    }
}
