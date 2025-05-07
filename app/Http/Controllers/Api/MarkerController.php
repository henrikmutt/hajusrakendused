<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function index()
    {
        return Marker::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $marker = Marker::create($data);
        return response()->json($marker, 201);
    }

    public function show($id)
    {
        return Marker::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $marker = Marker::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
        ]);

        $marker->update($data);
        return response()->json($marker);
    }

    public function destroy($id)
    {
        $marker = Marker::findOrFail($id);
        $marker->delete();
        return response()->json(null, 204);
    }
}
