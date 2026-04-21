<?php

namespace App\Http\Controllers;

use App\Http\Requests\GlassUseRequest;
use App\Models\GlassUse;
use Illuminate\Http\Request;

class GlassUseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $glassUses = GlassUse::with(['product', 'glass'])->get();
        return response()->json([
            'Message' => "get Glass Usage sucess",
            "data"    => $glassUses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GlassUseRequest $request)
    {
        $glassUse = GlassUse::create($request->validated());

        return response()->json(['message' => 'Glass use recorded', 'id' => $glassUse->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $glassUse = GlassUse::with(['product', 'glass'])->find($id);
        if (!$glassUse) {
            return response()->json(['error' => 'Glass use record not found'], 404);
        }
        return response()->json($glassUse);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
