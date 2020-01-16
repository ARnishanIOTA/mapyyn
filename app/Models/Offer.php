<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
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
     * relation with category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * relation with Provider
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }


    /**
     * relation with Images
     */
    public function images()
    {
        return $this->hasMany(OfferImage::class);
    }


    /**
     * relation with category
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }


    /**
     * relation with city
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    /**
     * relation with payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'offer_id');
    }
    
}
