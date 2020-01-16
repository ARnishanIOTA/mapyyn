<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileNotification extends Model
{
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
     * relation with offer
     */
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }


    /**
     * relation with client
     */
    public function client()
    {
        return $this->belongsTo(client::class);
    }


    /**
     * relation with provider
     */
    public function provider()
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * relation with Nitifications
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }


    /**
     * relation with request offers
     */
    public function requestOffer()
    {
        return $this->belongsTo(RequestOffer::class);
    }


    /**
     * relation with request offers
     */
    public function adminNotifications()
    {
        return $this->hasMany(AllNotifications::class, 'notification_id');
    }

}

