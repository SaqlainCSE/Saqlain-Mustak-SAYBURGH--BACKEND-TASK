<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json([
            'success' => true,
            'message' => 'all roles fetched',
            'data' => $roles,
        ], 200);
    }

    public function change_role(Request $request)
    {
        $user = User::find($request->user_id);
        $user->role_id = $request->role_id;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'user role has been changed',
        ], 200);
    }
}
