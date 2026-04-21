<?php

namespace App\Http\Controllers;

use App\Http\Requests\GlassRequest;
use App\Models\Glass;
use Illuminate\Http\Request;

class GlassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $glasses = Glass::all();
        return response()->json($glasses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GlassRequest $request)
    {
        $glass = Glass::create($request->validated());

        return response()->json([
            'message' => 'Glass created',
            'id'      => $glass->id
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $glass = Glass::find($id);
        if (!$glass) {
            return response()->json(['message' => 'Glass not found'], 404);
        }
        return response()->json($glass);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GlassRequest $request, $id)
    {
        $glass = Glass::find($id);
        if (!$glass) {
            return response()->json(['message' => 'Glass not found'], 404);
        }

        $glass->update($request->validated());

        return response()->json(['message' => 'Glass updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $glass = Glass::find($id);
        if (!$glass) {
            return response()->json(['message' => 'Glass not found'], 404);
        }

        $glass->delete();

        return response()->json(['message' => 'Glass deleted']);
    }
}
