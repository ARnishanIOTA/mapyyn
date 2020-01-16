<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SubscribeNotifications;

class Contact extends Model
{
    use Notifiable;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [''];


    protected static function boot() {
        parent::boot();

        static::created(function($model) { 
            $model->sendSubscribeNotification();            
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];


    public function sendSubscribeNotification()
    {
        $this->notify(new SubscribeNotifications());
    }
}
