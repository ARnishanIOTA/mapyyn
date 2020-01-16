<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
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
     * relation with user - one to many
     * 
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }


    /**
     * relation with client - one to many
     * 
     * @return object
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    /**
     * relation with provider - one to many
     * 
     * @return object
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }


    /**
     * relation with messages - many to one
     * 
     * @return object
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }

}
