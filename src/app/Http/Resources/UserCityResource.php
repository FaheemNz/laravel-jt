<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id'         =>  $this->id,
            'name'       =>  $this->name,
            'state'      =>  ($this->state) ? $this->state->name : '',
            'country'    =>  ($this->state && $this->state->country) ? $this->state->country->name : '',
            'flag_url'   =>  ($this->state && $this->state->country) ? $this->state->country->flag_url : '',
        ];
    }
}
