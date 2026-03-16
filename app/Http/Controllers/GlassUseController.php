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
      $glassUses = DB::table('tb_glass_uses')
                    ->join('tb_productnew', 'tb_glass_uses.product_id', '=', 'tb_productnew.id')
                    ->join('tb_glass', 'tb_glass_uses.glass_id', '=', 'tb_glass.id')
                    ->join('tb_stock', 'tb_stock.glass_id', '=', 'tb_glass.id')
                    ->join('users', 'tb_glass_uses.cashier_id', '=', 'users.id')
                    ->select(
                        'tb_glass_uses.id',
                        'tb_productnew.product_nameenglish',
                        'tb_glass.name as glass_name',
                        'tb_glass_uses.quantity_used',
                        'tb_stock.quantity as stock_quantity',
                        'users.name as cashier_name'
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
            'cashier_id'  => 'required|integer',
            'quantity_used' => 'required|integer|min:1'
        ]);
         $id = DB::table('tb_glass_uses')->insertGetId([
            'product_id' => $request->product_id,
            'glass_id' => $request->glass_id,
            'cashier_id'   =>$request->cashier_id,
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
