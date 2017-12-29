<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'name', 'description'];

    public function group()
    {
        return $this->hasOne('App\Group');
    }

    public function user()
    {
      return $this->hasOne('App\User');
    }
}
