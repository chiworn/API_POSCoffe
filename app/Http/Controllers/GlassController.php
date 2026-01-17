<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $glasses = DB::table('TB_Glass')->get();
        return response()->json([
            'Message' => 'Get Glass sucess',
            'data'    =>  $glasses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
        ]);
        
        $id = DB::table('TB_Glass')->insertGetId([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return response()->json(['message' => 'Glass created', 'id' => $id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $request->validate([
            'name' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
        ]);

        $updated = DB::table('TB_Glass')->where('id', $id)->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        if (!$updated) {
            return response()->json(['error' => 'Glass not found or no change'], 404);
        }

        return response()->json(['message' => 'Glass updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = DB::table('TB_Glass')->where('id', $id)->delete();
        if (!$deleted) {
            return response()->json(['error' => 'Glass not found'], 404);
        }
        return response()->json(['message' => 'Glass deleted']);

    }
}
