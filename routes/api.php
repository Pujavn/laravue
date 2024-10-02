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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
// Route::get('/users', [UserController::class, 'index'])->middleware('auth:sanctum');
// Route::get('/activate', [UserController::class, 'activateUser']);
Route::get('/activate', [AuthController::class, 'activateAccount']);
//get cities based on selected state
Route::get('/states/{state_id}/cities', [CityController::class, 'getCitiesByState']);


Route::resource('states', StateController::class);
Route::resource('cities', CityController::class);

Route::middleware('auth:sanctum')->group(function () {

    

    Route::resource('category',App\Http\Controllers\CategoryController::class)->only(['index','store','show','update','destroy']);
        
    // // Add other protected routes here

    // Route::resource('states', StateController::class);
    // Route::resource('cities', CityController::class);

    // //get cities based on selected state
    // Route::get('/states/{state_id}/cities', [CityController::class, 'getCitiesByState']);

    //logged user profile
    Route::get('/user/profile', [UserController::class, 'profile']);

    // Admin-specific routes
    Route::put('users/{id}/status', [UserController::class, 'updateStatus']); // Update user status
    Route::get('/users', [UserController::class, 'index']);    // Get list of users
    Route::get('/user/show/{id}', [UserController::class, 'show']); // Get a single user
    Route::put('/user/update/{id}', [UserController::class, 'update']); // Update user
    Route::delete('/user/{id}', [UserController::class, 'destroy']); // Delete user
});