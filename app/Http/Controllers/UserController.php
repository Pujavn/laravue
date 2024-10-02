<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function showRegistrationForm()
    {
        $states = State::all();
        $cities = City::all();

        return view('register', compact('states', 'cities'));
    }

    public function index()
    {
        // Ensure the authenticated user is an admin using a Gate
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Unauthorized'], 403); // Unauthorized access
        }

        // Get the ID of the currently authenticated user
        $currentUserId = auth()->id();

        // Retrieve all users except the current logged-in user
        $users = User::where('id', '!=', $currentUserId)->get();

        return response()->json($users);
    }

    // Update a user's status (admin only)
    public function updateStatus(Request $request, $id)
    {
        // Ensure the authenticated user is an admin
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Unauthorized'], 403); // Unauthorized access
        }

        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'status' => 'required|in:pending,active,inactive',
        ]);

        $user->update(['status' => $validatedData['status']]);
        return response()->json(['message' => 'User status updated successfully', 'user' => $user]);
    }

    // Show user information (admin only)
    public function show($id)
    {
        // Ensure the authenticated user is an admin
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Unauthorized'], 403); // Unauthorized access
        }

        // Find the user by ID, along with their associated states and cities
        $user = User::with(['states', 'cities'])->findOrFail($id);

        // Return the user data along with states and cities
        return response()->json([
            'user' => $user,
            'states' => $user->states, // Include the states
            'cities' => $user->cities  // Include the cities
        ]);
    }

    // Update user details including state and cities (admin only)
    public function update(Request $request, $id)
    {
        // Ensure the authenticated user is an admin
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Unauthorized'], 403); // Unauthorized access
        }

        // Find the user by ID
        $user = User::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Ignore the current user's email for uniqueness
            'status' => 'required|in:pending,active,inactive',
            'role' => 'required|in:user,admin',
            'state_ids' => 'array|required',  // State should be an array
            'city_ids' => 'array|required',   // City should be an array
        ]);

        // Update user details
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'status' => $validatedData['status'],
            'role' => $validatedData['role'],
        ]);

        // Sync the selected states and cities (Many-to-Many relationships)
        $user->states()->sync($validatedData['state_ids']); // Sync states
        $user->cities()->sync($validatedData['city_ids']);  // Sync cities

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user,
        ], 200);
    }

    // Delete a user (admin only)
    public function destroy($id)
    {
        // Ensure the authenticated user is an admin
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Unauthorized'], 403); // Unauthorized access
        }

        // Find the user by ID
        $user = User::findOrFail($id);

        // Prevent deletion of admin users
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Cannot delete an admin user!'], 403);
        }

        // Detach related states and cities before deleting the user
        $user->states()->detach(); // Detach all related states from the pivot table
        $user->cities()->detach(); // Detach all related cities from the pivot table

        // Delete the user from the database
        $user->delete();

        return response()->json(['message' => 'User deleted successfully!'], 200);
    }

    public function profile()
    {
        // This will automatically use the default guard (which is Sanctum for API routes)
        $user = auth()->user();

        // Return error if not authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['user' => $user], 200);
    }
}
