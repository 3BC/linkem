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
    protected $fillable = ['url', 'name', 'description','feed'];

    public function users(){
      return $this->belongsToMany('App\User');

    }
}
