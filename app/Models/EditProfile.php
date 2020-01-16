<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditProfile extends Model
{
    protected $guarded = [''];


    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}