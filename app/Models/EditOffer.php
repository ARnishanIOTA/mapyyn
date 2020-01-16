<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditOffer extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [''];


    /**
     * relation with client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    /**
     * relation with offer
     */
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
