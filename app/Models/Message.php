<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Defines the message model
 */

class Message extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'text', 'timestamp', 'user_id'];
}
