<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];
    protected $table = "roles";

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

/**
 * $role->users
 */
