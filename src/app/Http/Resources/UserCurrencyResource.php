<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'short_code' => $this->short_code,
            'symbol'     => $this->symbol,
            'flag_url'   => $this->flag_url,
            'rate'       => $this->rate
        ];
    }
}