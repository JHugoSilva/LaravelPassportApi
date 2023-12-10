<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully'
        ]);
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($credentials) {

            $user = auth()->user();
            $token = $user->createToken('myToken')->accessToken;

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $token
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Invalid login details'
            ]);
        }
    }

    public function profile()
    {
        $user = auth()->user();

        return response()->json([
            'status' => true,
            'message' => 'Profile information',
            'data' => $user
        ]);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'User logged out'
        ]);
    }
}
