<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Defines the user model.
 */

class User extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'full_name',
        'username',
        'password',
        'membership-status',
        'is_admin'
    ];
}
