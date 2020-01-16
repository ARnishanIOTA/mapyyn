<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MyOffersCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($page){
            return [
                'id'               => $page->id,
                'offer_id'         => $page->offer_id,
                'request_offer_id' => $page->request_offer_id,
                'provider_id'      => $page->provider_id,
                'client_id'        => $page->client_id,
                'transaction_id'   => $page->transaction_id,
                'status'           => $page->status,
                'created_at'       => date('Y-m-d', strtotime($page->created_at)),
                'updated_at'       => date('Y-m-d', strtotime($page->updated_at)),
                'files'            => $page->files == null ? (object) ['test'] : $page->files,
                'offer'            => $page->offer
            ];
        });
    }
}
