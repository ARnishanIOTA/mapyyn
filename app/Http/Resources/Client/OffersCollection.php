<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OffersCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($page) {
            return [
                'id'          => $page->id,
                'lat'         => $page->lat,
                'lng'         => $page->lng,
                'event_name'  => $page->event_name,
                'category_id' => $page->category_id,
                'hotel_level' => $page->hotel_level,
                'price'       => $page->price,
                'provider'    => $page->provider,
                'country'     => $page->country,
                'currency'    => $page->currency,
                'city'        => $page->city,
                'days'        => $page->days, 
                'from'        => $page->from,
                'to'          => $page->to,
                'end_at'      => $page->end_at,
            ];
        });
    }
}
