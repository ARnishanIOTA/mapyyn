<?php

namespace App\Http\Resources\Provider;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = $this->logo == null ? 'default.png' : $this->logo;
        return [
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'code' => $this->code,
            'logo'    => $image,
            'country' => $this->country,
            'city' => $this->city,
        ];
    }
}
