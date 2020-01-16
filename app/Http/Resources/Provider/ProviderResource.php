<?php

namespace App\Http\Resources\Provider;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
    public $token;
    
    public $provider;


    public function __construct($provider, $token)
    {
        $this->provider = $provider;
        $this->token    = $token;
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = $this->provider->logo == null ? 'default.png' : $this->provider->logo;
        return [
            'id'   => $this->provider->id,
            'first_name' => $this->provider->first_name,
            'last_name' => $this->provider->last_name,
            'email' => $this->provider->email,
            'logo'   => $image,
            'token' => 'Bearer '.$this->token
        ];
    }
}
