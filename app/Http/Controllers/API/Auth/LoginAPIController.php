<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginAPIController extends Controller
{
    public function createToken()
    {
        $user = User::first();
        $accessToken = $user->createToken('Token Name')->accessToken;
        return response()->json([
            'success' => true,
            'message' => 'User Details',
            'data' => $user,
            'accessToken' => $accessToken,
        ], 200);
    }

    public function refreshToken()
    {
        $user = User::first();
        $cookie = $user->refreshToken('Token Name')->cookie;
        return response()->json([
            'success' => true,
            'message' => 'User Details',
            'data' => $user,
            'cookie' => $cookie,
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validator = Validator::make($loginData, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Please give your email!',
            'password.required' => 'Please give your password!',
            'email.email' => 'Please give valid email address!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag(),
            ]);
        }

        if (!Auth::attempt($loginData)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email/password!',
            ], 400);
        } else {
            $user = User::find(Auth::user()->id);
            $access_token = $user->createToken('authToken')->accessToken;
            $refresh_token = $user->refreshToken('authToken')->cookie;
            return response()->json([
                'success' => true,
                'message' => 'Login Successful!',
                'data' => ['user' => $user, 'access_token' => $access_token, 'refresh_token' => $refresh_token],
            ], 200);
        }
    }
}
