<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public function index()
    {
        $states = State::all();
        return response()->json($states);
    }

    public function show($id)
    {
        $state = State::with('cities')->findOrFail($id);
        return response()->json($state);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        $state = State::create($request->only('name'));
        return response()->json($state, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        $state = State::findOrFail($id);
        $state->update($request->only('name'));
        return response()->json($state);
    }

    public function destroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete();
        return response()->json(null, 204);
    }
}
