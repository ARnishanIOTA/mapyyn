<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ContactUsNotifications;

class ContactUs extends Model
{
    use Notifiable;
    
    /** Table name */
    protected $table = 'contact_us';

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


    public function sendSubscribeNotification()
    {
        $this->notify(new ContactUsNotifications());
    }

}
