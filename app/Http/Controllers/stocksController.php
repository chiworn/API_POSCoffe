<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class stocksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         {
        $stocks = DB::table('TB_stock')
            ->join('TB_Glass', 'TB_stock.glass_id', '=', 'TB_Glass.id')
            ->select('TB_stock.id', 'TB_Glass.name as glass_name', 'TB_stock.quantity')
            ->get();

        return response()->json(
            [
                'Message' => "get Stock sucess",
                "data"    => $stocks
            ]
            );
    }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          $request->validate([
            'glass_id' => 'required|integer',
            'quantity' => 'required|integer|min:0'
        ]);

        $id = DB::table('TB_stock')->insertGetId([
            'glass_id' => $request->glass_id,
            'quantity' => $request->quantity
        ]);

        return response()->json(['message' => 'Stock added', 'id' => $id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $updated = DB::table('TB_stock')->where('id', $id)->update([
            'quantity' => $request->quantity
        ]);

        if (!$updated) {
            return response()->json(['error' => 'Stock not found or no change'], 404);
        }

        return response()->json(['message' => 'Stock updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $deleted = DB::table('TB_stock')->where('id', $id)->delete();
        if (!$deleted) {
            return response()->json(['error' => 'Stock not found'], 404);
        }

        return response()->json(['message' => 'Stock deleted']);
    }
}
