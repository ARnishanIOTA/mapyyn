<?php

namespace App\Models;

use App\Models\RequestOffer;
use Illuminate\Database\Eloquent\Model;

class RequestOfferProvider extends Model
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

    public function requestOffer()
    {
        return $this->belongsTo(RequestOffer::class);
    }

    
}
