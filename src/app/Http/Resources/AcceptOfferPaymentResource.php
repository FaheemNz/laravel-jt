<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AcceptOfferPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order = $this;
        $order->images = $this->images()->first()->name;
        $order->category = $this->category;
        $order->price = $this->offers()->where('id', $request->offer_id)->first()->price;
        $order->reward = $this->offers()->where('id', $request->offer_id)->first()->reward;
        $order->duty = $order->price / 100 * ($order->category->tariff);
        $order->service_fee = 1000;
        $order->total_payable = $order->price+$order->reward+$order->duty+$order->service_fee;

        dd(view('process_payment', compact($order)));
        return [
            "html" => view('process_payment', compact($order))
        ];
        // return parent::toArray($request);
    }
}
