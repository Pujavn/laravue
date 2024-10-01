<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationEmail;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:Male,Female',
            'state_ids' => 'array|required',  // Accepts multiple states
            'city_ids' => 'array|required',   // Accepts multiple cities
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'gender' => $request->gender,
            'status' => 'pending',  // Default status: pending activation
        ]);

        // Attach the selected states and cities to the user (Many-to-Many)
        $user->states()->attach($request->state_ids);
        $user->cities()->attach($request->city_ids);

        // Send activation email
        Mail::to($user->email)->send(new ActivationEmail($user));

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        // Validate the incoming request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log in using the provided credentials
        if (Auth::attempt($credentials)) {
            // Retrieve the authenticated user
            $user = Auth::user();

            // Check if the user's account is active
            if ($user->status !== 'active') {
                // If the user is not active, return a message to activate the account
                return response()->json(['message' => 'Please activate your account via the email we sent you.'], 403);
            }

            // If the user is active, create a token
            $token = $user->createToken('api-token')->plainTextToken;

            // Return the token in the response
            return response()->json(['token' => $token], 200);
        } else {
            // Return an error message if the credentials are invalid
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

    public function activateAccount(Request $request)
    {
        // Get the token from the URL
        $token = $request->query('token');

        // Find the access token in the personal_access_tokens table
        $personalAccessToken = PersonalAccessToken::findToken($token);

        if (!$personalAccessToken) {
            return response()->json(['message' => 'Invalid activation token'], 404);
        }

        // Find the user who owns the token
        $user = $personalAccessToken->tokenable;

        if ($user->status === 'active') {
            return response()->json(['message' => 'Your account is already activated'], 200);
        }

        // Activate the user's account
        $user->status = 'active';
        $user->save();

        // Delete the activation token after successful activation
        $personalAccessToken->delete();

        return response()->json(['message' => 'Your account has been activated'], 200);
    }
}
