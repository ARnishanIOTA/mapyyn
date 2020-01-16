<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
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
     * relation with provider
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * relation with client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
