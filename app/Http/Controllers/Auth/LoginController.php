<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            //code...
            if (Auth::attempt($credentials)) {
                // $request->session()->regenerate();

                $user = Auth::user();

                return response()->json(['success' => true, 'user' => $user, 'token' => $user->createToken('auth_token')->plainTextToken], 200);
            }
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
