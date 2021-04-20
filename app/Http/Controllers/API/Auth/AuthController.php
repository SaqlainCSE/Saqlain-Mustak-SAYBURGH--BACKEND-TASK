<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function auth_user()
    {
        $user = User::find(Auth::user()->id);
        if (is_null($user)) {
            return response()->json([
                'success' => false,
                'message' => 'No authenticate user',
            ], 200);
        }
        return response()->json([
            'success' => true,
            'message' => 'authenticated user details fetched',
            'data' => [
                'user' => $user,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
            return response()->json([
                'message' => 'Successfully logged out'
            ], 200);
    }

    
}
