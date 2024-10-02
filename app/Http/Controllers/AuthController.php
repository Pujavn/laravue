<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationEmail;
use Illuminate\Support\Str;


class AuthController extends Controller
{
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

        // Generate a unique activation token
        $activationToken = Str::random(60);  // Generates a random 32-character token

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'gender' => $request->gender,
            'status' => 'pending',  // Default status: pending activation
            'activation_token' => $activationToken,  // Store the activation token
        ]);

        // Attach the selected states and cities to the user (Many-to-Many)
        $user->states()->attach($request->state_ids);
        $user->cities()->attach($request->city_ids);

        // Send activation email with the activation token
        Mail::to($user->email)->send(new ActivationEmail($user, $activationToken));  // Pass token to email

        return response()->json([
            'message' => 'User registered successfully! Please check your email to activate your account.',
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
            $user = Auth::user();  // Consistently use Auth::user()

            // Check if the user's account is active
            if ($user->status !== 'active') {
                // If the user is not active, return a message to activate the account
                return response()->json(['message' => 'Please activate your account via the email we sent you.'], 403);
            }

            // If the user is active, create a token
            $token = $user->createToken('api-token')->plainTextToken;

            // Return the token and the user's role in the response
            return response()->json([
                'token' => $token,
                'role' => $user->role,  // Assuming your User model has a 'role' field
            ], 200);
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

        // Find the user by the activation token
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid activation token'], 404);
        }

        if ($user->status === 'active') {
            return response()->json(['message' => 'Your account is already activated'], 200);
        }

        // Activate the user's account
        $user->status = 'active';
        $user->activation_token = null;  // Clear the activation token after activation
        $user->save();

        return response()->json(['message' => 'Your account has been activated'], 200);
    }
}
