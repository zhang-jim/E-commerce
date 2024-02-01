<?php

namespace App\Http\Controllers\store;

use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;

class MemberController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return response()->json(['user' => $user]);
    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string',
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => 'user' . time(),
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
            // dd($exception->getMessage());
            // return response()->json($exception->getMessage());
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}
