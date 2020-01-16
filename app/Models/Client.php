<?php

namespace App\Models;

use App\Models\Offer;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Notifications\WelcomeNotification;
use App\Notifications\ActivateNotification;
use App\Notifications\BuyOfferNotification;
use App\Notifications\ChangePasswordNotification;
use App\Notifications\ComplatePaymentNotification;
use App\Notifications\NewRequestOfferNotification;
use App\Notifications\ClientResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable implements JWTSubject
{
    use Notifiable;
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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_code', 'fcm_token'
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }



    /**
     * relation with offers
     */
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'client_offers', 'client_id', 'offer_id')->withPivot('status');
    }


    public function sendActivateNotification()
    {
        $this->notify(new ActivateNotification());
    }


    public function sendWelcomeNotification()
    {
        $this->notify(new WelcomeNotification());
    }


    public function sendNewRequestOfferNotification()
    {
        $this->notify(new NewRequestOfferNotification());
    }


    /**
     * Send password reset notification
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ClientResetPasswordNotification($token));
    }


    public function sendChangePasswordNotification()
    {
        $this->notify(new ChangePasswordNotification());
    }

    public function sendByOfferNotification($model)
    {
        $this->notify(new BuyOfferNotification($model));
    }


    public function sendComplatePaymentNotification()
    {
        $this->notify(new ComplatePaymentNotification());
    }

}
