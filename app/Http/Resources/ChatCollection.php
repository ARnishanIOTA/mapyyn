<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatCollection extends ResourceCollection
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
                'id'               => (int) $page->id,
                'client_id'        => (int) $page->client_id,
                'client_name'      => optional($page->client)->first_name . ' ' . optional($page->client)->last_name,
                'provider_id'      => (int) $page->provider_id,
                'provider_name'    => optional($page->provider)->first_name . ' ' . optional($page->provider)->last_name,
                'message'          => $page->messages->first(),
                'offer_type'       => (int) $page->offer_type,
                'type'       => (int) $page->type,
                'offer_id'         => (int) $page->offer_id,
                'request_offer_id' => (int) $page->request_offer_id,
                'created_at'       => date_format($page->created_at,"Y-m-d"),
            ];
        });
    }
}
