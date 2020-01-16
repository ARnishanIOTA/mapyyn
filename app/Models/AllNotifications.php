<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllNotifications extends Model
{
    protected $guarded = [''];


    public function notification()
    {
        return $this->belongsTo(MobileNotification::class);
    }
}
