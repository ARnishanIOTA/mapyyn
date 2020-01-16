<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'description' => $this->description,
            'event_name' => $this->event_name,
            'category_id' => $this->category_id,
            'hotel_level' => $this->hotel_level,
            'persons' => $this->persons,
            'transport' => $this->transport,
            'days' => $this->days,
            'price' => $this->price,
            'currency'    => $this->currency,
            'location' => $this->location,
            'countries' => $this->countries,
            'city' => $this->city,
            'provider' => $this->provider,
            'end_at'      => $this->end_at,
        ];
    }
}
