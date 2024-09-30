<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Issue an API token for the user
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request)
{
    // Validate the incoming request
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // // Attempt to log in the user using the provided credentials
    // if (!Auth::attempt($credentials)) {
    //     return response()->json(['message' => 'Invalid login credentials'], 401);
    // }

    // // Get the authenticated user
    // $user = Auth::user();

    // // Generate a personal access token using Sanctum
    // $token = $user->createToken('api-token')->plainTextToken;

    // // Return the token in the response
    // return response()->json(['token' => $token]);
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['token' => $token]);
    } else {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}

    // Get authenticated user details
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Logout user (invalidate token)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
