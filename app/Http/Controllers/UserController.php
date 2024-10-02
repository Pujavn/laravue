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

        // Find the user by ID, along with their permanent and temporary states/cities
        $user = User::with(['permanentStates', 'temporaryStates', 'permanentCities', 'temporaryCities'])->findOrFail($id);

        // Return the user data along with states and cities
        return response()->json([
            'user' => $user,
            'permanent_states' => $user->permanentStates,
            'temporary_states' => $user->temporaryStates,
            'permanent_cities' => $user->permanentCities,
            'temporary_cities' => $user->temporaryCities,
        ]);
    }

    // Update user details including state and cities (admin only)
    public function update(Request $request, $id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Find the user by ID
        $user = User::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Ignore the current user's email for uniqueness
            'status' => 'required|in:pending,active,inactive',
            'role' => 'required|in:user,admin',
            'permanent_state_id' => 'required|integer',
            'permanent_city_ids' => 'required|array',
            'temporary_state_id' => 'required|integer',
            'temporary_city_ids' => 'required|array',
        ]);

        // Update user details
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'status' => $validatedData['status'],
            'role' => $validatedData['role'],
        ]);

        // Sync the selected permanent and temporary states (Many-to-Many relationships)
        $user->permanentStates()->sync([$validatedData['permanent_state_id'] => ['type' => 'permanent']]);
        $user->temporaryStates()->sync([$validatedData['temporary_state_id'] => ['type' => 'temporary']]);

        // Sync the selected permanent cities with type 'permanent'
        $permanentCityData = [];
        foreach ($validatedData['permanent_city_ids'] as $cityId) {
            $permanentCityData[$cityId] = ['type' => 'permanent'];
        }
        $user->permanentCities()->sync($permanentCityData);

        // Sync the selected temporary cities with type 'temporary'
        $temporaryCityData = [];
        foreach ($validatedData['temporary_city_ids'] as $cityId) {
            $temporaryCityData[$cityId] = ['type' => 'temporary'];
        }
    $user->temporaryCities()->sync($temporaryCityData);

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

        // Detach related states and cities from the pivot tables before deleting the user
        $user->permanentStates()->detach();  
        $user->temporaryStates()->detach();
        $user->permanentCities()->detach();
        $user->temporaryCities()->detach();

        // Delete the user from the database
        $user->delete();

        return response()->json(['message' => 'User deleted successfully!'], 200);
    }

    public function profile()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['user' => $user], 200);
    }
}
