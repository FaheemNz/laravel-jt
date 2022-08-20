<?php


namespace App\Services;


use App\Payment;
use App\Services\Interfaces\PayableServiceInterface;
use App\SystemSetting;
use App\Lib\Helper;

class PayableService implements PayableServiceInterface
{
    /**
     * @param Payment $payment
     * @return array
     */
    public function generatePayable($payment)
    {
        $customer_data    = [];
        $traveller_data   = [];
        $brrring_data     = [];

        if($payment){
            $order                          = $payment->order;
            $offer                          = $payment->offer ?: $payment->counterOffer->offer;
            $counterOffer                   = $payment->counterOffer;

            $order_currency                 = $order->currency_id;
            $other_currency                 = auth()->user()->currency_id;

            $price                          = $offer->price;
            $reward                         = $counterOffer? $counterOffer->reward : $offer->reward;

            $item_price_collected           = $price;
            $item_reward_collected          = $reward;
            $item_duty_collected            = $price * $order->customer_duty_charges_percentage;
            $customer_service_charges       = $price * $order->customer_service_charges_percentage;
            $total_collected_from_customer  = ($item_price_collected + $item_reward_collected + $item_duty_collected) + $customer_service_charges;

            $item_purchased_price           = $order->item_purchased_amount;
            $item_duty_paid                 = $order->custom_duty_charges_amount?: 0;
            $final_item_amount              = $item_purchased_price + $item_duty_paid;

            $traveler_service_charges       = $item_reward_collected * $order->traveler_service_charges_percentage;
            $total_earning_of_traveller     = ($item_reward_collected) - $traveler_service_charges;
            $total_expense_on_item          = $item_purchased_price < $item_price_collected? $item_purchased_price : $item_price_collected;
            $total_expense_on_duty          = $item_duty_paid < $item_duty_collected? $item_duty_paid : $item_duty_collected;

            $brrring_profit                 = $customer_service_charges + $traveler_service_charges;
            $customer_returnable_price      = $item_purchased_price < $item_price_collected? ($item_price_collected - $item_purchased_price) : 0;
            $customer_returnable_duty       = $item_duty_paid < $item_duty_collected? ($item_duty_collected - $item_duty_paid) : 0;

            if($brrring_profit > 0){
                $order->update([
                    'profit' =>  Helper::convertCurrency($order->currency_id, 1, $brrring_profit)
                ]);
            }


            $customer_data = [
                "price_paid"                => number_format($item_price_collected, 2),
                "other_price_paid"          => number_format(Helper::convertCurrency($order_currency, $other_currency, $item_price_collected),2),

                "price_used"                => number_format($item_purchased_price,2),
                "other_price_used"          => number_format(Helper::convertCurrency($order_currency, $other_currency,$item_purchased_price), 2),

                "price_returnable"          => number_format($customer_returnable_price, 2),
                "other_price_returnable"    => number_format(Helper::convertCurrency($order_currency, $other_currency,$customer_returnable_price),2),

                "duty_paid"                 => number_format($item_duty_collected,2),
                "other_duty_paid"           => number_format(Helper::convertCurrency($order_currency, $other_currency,$item_duty_collected),2),

                "duty_used"                 => number_format($item_duty_paid,2),
                "other_duty_used"           => number_format(Helper::convertCurrency($order_currency, $other_currency,$item_duty_paid),2),

                "duty_returnable"           => number_format($customer_returnable_duty,2),
                "other_duty_returnable"     => number_format(Helper::convertCurrency($order_currency, $other_currency,$customer_returnable_duty),2),

                "total_returnable"          => number_format($customer_returnable_price + $customer_returnable_duty,2),
                "other_total_returnable"    => number_format(Helper::convertCurrency($order_currency, $other_currency,($customer_returnable_price + $customer_returnable_duty)),2)
            ];

            $traveller_data = [
                "item_expense"              => number_format($total_expense_on_item,2),
                "other_item_expense"        => number_format(Helper::convertCurrency($order_currency, $other_currency,$total_expense_on_item),2),

                "reward_collected"          => number_format($item_reward_collected, 2),
                "other_reward_collected"    => number_format(Helper::convertCurrency($order_currency, $other_currency,$item_reward_collected), 2),

                "duty_expense"              => number_format($total_expense_on_duty, 2),
                "other_duty_expense"        => number_format(Helper::convertCurrency($order_currency, $other_currency,$total_expense_on_duty), 2),

                "service_charges"           => number_format($traveler_service_charges, 2),
                "other_service_charges"     => number_format(Helper::convertCurrency($order_currency, $other_currency,$traveler_service_charges), 2),

                "reward_earning"            => number_format($total_earning_of_traveller, 2),
                "other_reward_earning"      => number_format(Helper::convertCurrency($order_currency, $other_currency,$total_earning_of_traveller), 2),

                "total_receivable"          => number_format($total_earning_of_traveller + $total_expense_on_item + $total_expense_on_duty, 2),
                "other_total_receivable"    => number_format(Helper::convertCurrency($order_currency, $other_currency,($total_earning_of_traveller + $total_expense_on_item + $total_expense_on_duty)), 2)
            ];

            $brrring_data = [
                "profit"        => number_format($brrring_profit, 2),
                "other_profit"  => number_format(Helper::convertCurrency($order_currency, $other_currency,$brrring_profit), 2)
            ];
        }

        return [
           "customer"   => $customer_data,
            "traveller" => $traveller_data,
            "brrring"   => $brrring_data
        ];

    }
}
