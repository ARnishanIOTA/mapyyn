<?php

namespace App;

use App\Models\Permission;
use Illuminate\Notifications\Notifiable;
use App\Notifications\BackendResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','image', 'permission_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new BackendResetPasswordNotification($token));
    }


    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
