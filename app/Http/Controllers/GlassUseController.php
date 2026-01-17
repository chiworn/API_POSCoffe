<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlassUseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $glassUses = DB::table('TB_ Glass_use')
            ->join('TB_Products', 'TB_ Glass_use.product_id', '=', 'TB_Products.id')
            ->join('TB_Glass', 'TB_ Glass_use.glass_id', '=', 'TB_Glass.id')
            ->join('TB_stock', 'TB_ Glass_use.glass_id', '=', 'TB_stock.id')
            ->select(
                'TB_ Glass_use.id',
                'TB_Products.product_nameenglish',
                'TB_Glass.name as glass_name',
                'TB_ Glass_use.quantity_used',
                'TB_stock.quantity'
            )
            ->get();

        return response()->json($glassUses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'product_id' => 'required|integer',
            'glass_id' => 'required|integer',
            'quantity_used' => 'required|integer|min:1'
        ]);
         $id = DB::table('TB_ Glass_use')->insertGetId([
            'product_id' => $request->product_id,
            'glass_id' => $request->glass_id,
            'quantity_used' => $request->quantity_used
        ]);

        return response()->json(['message' => 'Glass use added', 'id' => $id]);
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
