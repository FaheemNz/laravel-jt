<?php


namespace App\Services;


use App\ChatRoom;
use App\Currency;
use App\Lib\Helper;
use App\Notifications\CustomerPaysNotification;
use App\Offer;
use App\Order;
use App\Payment;
use App\Services\Interfaces\PaymentServiceInterface;
use App\SystemSetting;
use App\Transaction;
use App\Utills\Constants\OfferStatus;
use App\Utills\Constants\OrderStatus;
use Illuminate\Support\Str;

class PaymentService implements PaymentServiceInterface
{
    protected $Traveller_Service_Charges_Percentage;
    protected $Customer_Service_Charges_Percentage;
    protected $Customer_Duty_Charges_Percentage;

    public function __construct()
    {
        $settings = SystemSetting::all();

        $this->Traveller_Service_Charges_Percentage   = $settings->where("key","traveler_service_charges_percentage")->first()->value / 100;
        $this->Customer_Service_Charges_Percentage    = $settings->where("key","customer_service_charges_percentage")->first()->value / 100;
        $this->Customer_Duty_Charges_Percentage       = $settings->where("key","customer_duty_charges_percentage")->first()->value / 100;

    }

    public function createOrderPayment(Order $order)
    {
        $offer        = $order->offers()->where("status", OfferStatus::PAYMENT_IN_PROGRESS)->first();
        $counterOffer = $order->counterOffers()->where("status", OfferStatus::PAYMENT_IN_PROGRESS)->first();
        $data        = [];

        if($offer){
            $price  = $offer->price;
            $reward = $offer->reward;
            $data['offer_id'] = $offer->id;
        }

        if($counterOffer){
            $price                      = $counterOffer->offer->price;
            $reward                     = $counterOffer->reward;
            $data['counter_offer_id']   = $counterOffer->id;

            $offer                      = $counterOffer->offer;
        }

        $paymentExists  = Payment::where("order_id", $offer->order_id)->first();
        $new_price      = $price + $reward + ($price * $this->Customer_Duty_Charges_Percentage) + ($price * $this->Customer_Service_Charges_Percentage);

        $currency = Currency::where('short_code', 'PKR')->first();

        if($paymentExists) {
            $paymentExists->update([
                'user_id'      =>  auth()->id(),
                'amount'       =>  $new_price,
                'pkr_amount'   =>  Helper::convertCurrency($order->currency_id, $currency->id, $new_price),
                'traveler_id'  =>  $offer->user_id,
            ]);
        }else{
            $data['user_id']       =  auth()->id();
            $data['order_id']      =  $order->id;
            $data['status']        =  'progress';
            $data['amount']        =  $new_price;
            $data['pkr_amount']    =  Helper::convertCurrency($order->currency_id, $currency->id, $new_price);
            $data['customer_id']   =  $order->user_id;
            $data['traveler_id']   =  $offer->user_id;
            $data['type']          =  'credit';

            $paymentExists = Payment::create($data);
        }


        return $paymentExists;
    }


    public function clearPayment(Order $order, string $ref)
    {
        $paymentExists = Payment::where("order_id", $order->id);
//        $user = auth()->id();
        $user = $order->user;

//        if(!auth()->user()->isAdmin){
//            $paymentExists = $paymentExists->where("customer_id", $user->id);
//        }
        $paymentExists = $paymentExists->first();

        if(!$paymentExists){
            return false;
        }

        $order->update([
            "status"        => OrderStatus::PAID,
            "traveler_id"   => $paymentExists->traveler_id
        ]);

        $offer_id = null;

        if($paymentExists->counterOffer){
            $offer_id = $paymentExists->counterOffer->offer_id;
            $paymentExists->counterOffer()->update([
                "status" => OfferStatus::ACCEPTED
            ]);
        }

        if($paymentExists->offer){
            $offer_id = $paymentExists->offer_id;
            $paymentExists->offer()->update([
                "status" => OfferStatus::ACCEPTED
            ]);
        }

        $currency = Currency::where('short_code', 'PKR')->first();

        Transaction::create([
            "amount"                => $paymentExists->amount,
            "pkr_amount"            => $paymentExists->pkr_amount,
            "currency_id"           => $currency->id,
            "source"                => "creditcard",
            "transaction_details"   => "Payment Has Been Cleared for Order# ".$order->id." (" . $order->name . " )",
            "status"                => "settled",
            "ref_no"                => $ref ?: Str::uuid(),
            "user_id"               => $user->id,
            "order_id"              => $order->id,
        ]);

        $paymentExists->update(['status' => 'paid']);

        // Reject All Other Traveller's Offers On This Order
        $order->offers()
            ->where("user_id","<>", $paymentExists->traveler_id)
            ->update([
                "status" => OfferStatus::REJECTED
            ]);

        if($order->counterOffers){
            $order->counterOffers()
                ->update([
                    "status" => OfferStatus::REJECTED
                ]);
        }

        ChatRoom::create([
            'offer_id'      => $offer_id,
            'traveler_id'   => $paymentExists->traveler_id,
            'customer_id'   => $paymentExists->customer_id
        ]);

        $title = $paymentExists->customer->frist_name ." ".$paymentExists->customer->last_name . " has made the payment. Brrring has just received the item price from the customer";
        $paymentExists->traveller->notify(new CustomerPaysNotification($paymentExists, $title));

        return true;
    }
}
