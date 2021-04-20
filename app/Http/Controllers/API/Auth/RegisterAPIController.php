<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RegisterAPIController extends Controller
{
    public function register(Request $request)
    {
        $loginData = $request->all();
        $validator = Validator::make($loginData, [
            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'max:60', 'confirmed'],
        ], [
            'username.required' => 'Please give your username!',
            'password.required' => 'Please give your password!',
            'email.required' => 'Please give your email!',
            'email.email' => 'Give a valid email address!',
            'email.unique' => 'Email has been used!',
            'username.unique' => 'Username has been used!',
            'password.confirmed' => "Password didn't match!",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag(),
            ], 404);
        }

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = 2; // initially everybody's role id-2 is reader & role id-1 for admin;
        $user->activation_token = Str::random(60);
        $user->save();

        $user = User::find($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Registration Successful!',
            'data' => ['user' => $user],
        ], 201);
    }

}
