<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SubscribeNotifications;

class Subscribe extends Model
{
    use Notifiable;
    
    protected $guarded = [''];


    protected static function boot() {
        parent::boot();

        static::created(function($model) { 
            $model->sendSubscribeNotification();            
        });
    }


    public function sendSubscribeNotification()
    {
        $this->notify(new SubscribeNotifications());
    }
}
