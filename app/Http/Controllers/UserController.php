<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\State;
use App\Models\City;

class UserController extends Controller
{
    public function showRegistrationForm()
    {
        $states = State::all();
        $cities = City::all();

        return view('register', compact('states', 'cities'));
    }
}
