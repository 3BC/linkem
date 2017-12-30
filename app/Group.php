<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'private'];

    /**
     * Get the user that owns the group.
     */
    public function owners()
    {
        return $this->belongsToMany('App\User', 'group_owner');
    }

    /**
     * The moderators that belong to the group.
     */
    public function moderators()
    {
        return $this->belongsToMany('App\User', 'group_moderator');
    }

    /**
     * The followers that belong to the group.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'group_user');
    }

    public function contributors(){
      return $this->belongsToMany('App\User', 'contributor_group');
    }

    public function links()
    {
      return $this->hasMany('App\Link');
    }
}
