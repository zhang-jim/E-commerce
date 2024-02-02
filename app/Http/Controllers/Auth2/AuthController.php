<?php

namespace App\Http\Controllers\Auth2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function memberLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('user')->attempt($credentials)) {
            $user = Auth::guard('user')->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    public function adminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function memberDestory(Request $request)
    {
        Auth::guard('user')->logout();
        return response()->json(['message' => 'Logout successful']);
    }

    public function adminDestory(Request $request)
    {
        $request->user('admin')->tokens()->delete();
        return response()->json(['message' => 'Logout successful']);
    }

    public function memberUser()
    {
        $memberUser = Auth::user();
        return response()->json(['user' => $memberUser]);
    }
    public function adminUser()
    {
        $adminUser = Auth::guard('admin')->user();
        return response()->json(['user' => $adminUser]);
    }
    
}
