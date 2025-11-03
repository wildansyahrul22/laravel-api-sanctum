<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    try {
      //code...
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);

      return response([
        'success' => true,
        'message' => 'Successfully registered',
        'user' => $user
      ]);
    } catch (QueryException $e) {
      if ($e->errorInfo[1] === 1062) { // MySQL duplicate entry code
        return response()->json([
          'message' => 'Email already exists'
        ], 409);
      }
      return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
  }
}
