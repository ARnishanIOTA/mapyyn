<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public $token;
    
    public $client;


    public function __construct($client, $token)
    {
        $this->client = $client;
        $this->token = $token;
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = $this->client->image == null ? 'default.png' : $this->client->image;
        return [
            'id'        => $this->client->id,
            'first_name' => $this->client->first_name,
            'last_name' => $this->client->last_name,
            'email'     => $this->client->email,
            'image'     => $image,
            'currency'  => $this->client->currency,
            'token'     => 'Bearer '.$this->token
        ];
    }
}
