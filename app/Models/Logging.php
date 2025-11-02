<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'action'
    ];
}
