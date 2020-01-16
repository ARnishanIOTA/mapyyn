<?php

namespace App\Http\Resources\Provider;

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
                if($page->requestOffer == null){
                    $requestOffer = (object) ['test'];
                }else{
                    $providerOffer = $page->requestOffer->providerOffer()
                        ->where('provider_id', auth('apiProvider')->id())
                        ->first();
                    if($providerOffer == null){
                        $providerOffer = (object) ['test'];
                    }
                    $requestOffer = [
                        'id'          => $page->requestOffer->id,
                        'category_id' => $page->requestOffer->category_id,
                        'country'     => $page->requestOffer->country,
                        'city'        => $page->requestOffer->city,
                        'providerOffer' => $providerOffer
                    ];
                }
                if($page->offer == null){
                    $offer = (object) ['test'];
                }else{
                    $offer = $page->offer;
                }
                return [
                    'id'            => $page->id,
                    'title'         => $page->title,
                    'message'       => $page->message,
                    'chat_id'       => (int) $page->chat_id,
                    'type'          => $page->type,
                    'user_type'          => $page->user_type,
                    'provider_id'   => $page->provider_id,
                    'client_id'     => $page->client_id,
                    'offer_id'      => $page->offer_id,
                    'request_offer_id' => $page->request_offer_id,
                    'offer'         => $offer,
                    'requestOffer'  =>  $requestOffer,
                ];
            
        });

        
    }
}
