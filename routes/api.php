<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/activate-account', [AuthController::class, 'activateAccount']);

Route::get('/states', [StateController::class, 'index']); // List all states
Route::get('/states/{state_id}/cities', [CityController::class, 'getCitiesByState']);

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Authenticated User Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/user/profile', [UserController::class, 'profile']);

    // State and City Routes
    
    Route::resource('states', StateController::class)->only(['store', 'update', 'destroy']); // Protected
    Route::resource('cities', CityController::class)->only(['store', 'update', 'destroy']);   // Protected

    // Admin User Management Routes
    Route::middleware('can:isAdmin')->group(function () {
        Route::put('users/{id}/status', [UserController::class, 'updateStatus']); // Update user status
        Route::get('/users', [UserController::class, 'index']); // List all users except current logged-in user
        Route::get('/user/show/{id}', [UserController::class, 'show']); // Get a specific user
        Route::put('/user/update/{id}', [UserController::class, 'update']); // Update user
        Route::delete('/user/{id}', [UserController::class, 'destroy']); // Delete user
    });
});

// Category Routes (Protected)
Route::middleware('auth:sanctum')->resource('category', App\Http\Controllers\CategoryController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy']);