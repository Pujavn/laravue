<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('state')->get();
        return response()->json($cities);
    }

    public function show($id)
    {
        $city = City::with('state')->findOrFail($id);
        return response()->json($city);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'state_id' => 'required|exists:states,id',
        ]);
        $city = City::create($request->only('name', 'state_id'));
        return response()->json($city, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'state_id' => 'required|exists:states,id',
        ]);
        $city = City::findOrFail($id);
        $city->update($request->only('name', 'state_id'));
        return response()->json($city);
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return response()->json(null, 204);
    }

    /**
     * Get cities based on selected state
     */
    public function getCitiesByState($state_id)
    {
        $cities = City::where('state_id', $state_id)->get();
        return response()->json($cities);
    }
}
