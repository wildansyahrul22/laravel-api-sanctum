<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Logging extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'ip_address',
        'action'
    ];

    public static function record($message)
    {
        $user = Auth::user();

        Logging::create([
            'user_id' => $user ? $user->id : null,
            'ip_address' => request()->ip(),
            'message' => $message,
            'action' => request()->method()
        ]);
    }
}
