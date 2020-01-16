<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestOffer extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [''];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];


    /**
     * relation with Provider
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }


    /**
     * relation with client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    /**
     * relation with request offer provider
     */
    public function providerOffer()
    {
        return $this->hasMany(RequestOfferProvider::class, 'request_offer_id');
    }


    /**
     * relation with interests
     */
    public function interests()
    {
        return $this->hasMany(Interest::class);
    }


    /**
     * relation with payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'request_offer_id');
    }
}
