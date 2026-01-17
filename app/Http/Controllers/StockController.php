<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'product_ID' => 'required|integer|exists:TB_Products,id',
            'Glass_id'   =>  'required|integer',
            'quantity'   => 'required|integer|min:1'
        ]);

        // Get glass product
        $product = DB::table('TB_Products')
                // ->where('id', $request->product_ID)
                ->where('stock', $request->Glass_id)
                ->first();

        if (!$product) {
             return response()->json(['error' => 'Glass product not found'], 404);
        }

        $stock = DB::table('TB_stocks')
            ->where('Glass_id', $product->stock)
            ->first();
            

        if (!$stock) {
            return response()->json(['error' => 'No stock to subtract'], 400);
        }

        // Subtract quantity safely
        $newQuantity = $stock->quantity - $request->quantity;

        if ($newQuantity < 0) {
            return response()->json(['error' => 'Not enough stock'], 400);
        }

         DB::table('TB_stocks')->updateOrInsert(
            ['product_ID' => $product->id],
            // ['Glass_id' => $product->stock],
            ['quantity' => $newQuantity]
        );
        return response()->json(['message' => 'Stock input success']);
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
