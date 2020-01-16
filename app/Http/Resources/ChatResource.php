<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'id'              => $this->id,
            'client_id'       => $this->client_id,
            'client_name'     => optional($this->client)->first_name . ' ' . optional($this->client)->last_name,
            'client_image'    => optional($this->client)->image == null ? 'default.png' : optional($this->client)->image,
            'provider_id'     => $this->provider_id,
            'provider_name'   => optional($this->provider)->first_name . ' ' . optional($this->provider)->last_name,
            'provider_image'  => optional($this->provider)->image == null ? 'default.png' : optional($this->provider)->image,
            'type'            => $this->type,
            'messages'        => $this->messages->transform(function($page){
                return [
                    'id'            => $page->id,
                    'message'       => $page->message,
                    'type'          => $page->type,
                    'created_at'    => date_format($page->created_at,"Y-m-d"),
                ];
            }),
        ];
    }
}
