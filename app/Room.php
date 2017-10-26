<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * Get the user that owns the room.
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The moderators that belong to the room.
     */
    public function moderators()
    {
        return $this->belongsToMany('App\User', 'room_moderators');
    }

    /**
     * The followers that belong to the room.
     */
    public function followers()
    {
        return $this->belongsToMany('App\User', 'room_followers');
    }
}
