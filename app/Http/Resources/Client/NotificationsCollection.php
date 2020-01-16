<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationsCollection extends ResourceCollection
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
            if($page->offer == null){
                $offer = (object) ['test'];
            }else{
                $offer = $page->offer;
            }

            if($page->requestOffer == null){
                $requestOffer = (object) ['test'];

            }else{
                $requestOffer = $page->requestOffer;
            }
            return [
                'id'               => $page->id,
                'title'            => $page->title,
                'message'          => $page->message,
                'type'             => $page->type,
                'user_type'        => $page->user_type,
                'chat_id'          => $page->chat_id,
                'provider_id'      => $page->provider_id,
                'client_id'        => $page->client_id,
                'offer_id'         => $page->offer_id,
                'request_offer_id' => $page->request_offer_id,
                'offer'            => $offer,
                'requestOffer'     => $requestOffer, 
            ];
        });
    }
}
