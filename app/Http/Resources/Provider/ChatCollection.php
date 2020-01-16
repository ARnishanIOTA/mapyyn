<?php

namespace App\Http\Resources\Provider;

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
                    'id'         => $page->id,
                    'message'    => $page->message,
                    'provider'   => $page->provider->first_name. ' ' .$page->provider->last_name,
                    'created_at' => date_format($page->created_at,"Y-m-d"),
                    'type'       => $page->type
                ];
        });
        
    }
}
