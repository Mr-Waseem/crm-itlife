<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = array('name');

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_roles', 'role_id', 'user_id');
    }
}