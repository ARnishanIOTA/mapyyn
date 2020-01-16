<?php

namespace App\Models;

use App\Models\EditProviderCategory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ActivateNotification;
use App\Notifications\ChangePasswordNotification;
use App\Notifications\ProviderWelcomeNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ProviderResetPasswordNotification;

class Provider extends Authenticatable implements JWTSubject
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
     * The Categories that belong to the Provider.
     */
    public function categories()
    {
        return $this->hasMany(ProviderCategory::class);
    }


     /**
     * The Categories that belong to the Provider.
     */
    public function editCategories()
    {
        return $this->hasMany(EditProviderCategory::class, 'provider_id');
    }


    /**
     * The Categories that belong to the Provider.
     */
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }


    /**
     * Send password reset notification
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ProviderResetPasswordNotification($token));
    }


    public function sendWelcomeNotification()
    {
        $this->notify(new ProviderWelcomeNotification());
    }


    public function sendActivateNotification()
    {
        $this->notify(new ActivateNotification());
    }


    public function sendChangePasswordNotification()
    {
        $this->notify(new ChangePasswordNotification());
    }
    
}
