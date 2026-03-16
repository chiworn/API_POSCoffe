<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = DB::table('tb_items')
        ->Join('tb_productnew', 'tb_items.product_id', '=', 'tb_productnew.id')
         ->join('tb_categories','tb_productnew.category','=','tb_categories.id')
        ->select(
            'tb_items.*',
            'tb_productnew.product_namekhmer',
            'tb_productnew.product_nameenglish',
            'tb_productnew.price',
            'tb_productnew.stock',
            'tb_productnew.category',
            'tb_categories.category_name',
            'tb_categories.description',
            'tb_productnew.image',
            'tb_productnew.image_public_id' 
             )->get();
        return response()->json([
            'Message' => 'Get Items successs',
            'Data'    => $items 
         ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
     {
        $request->validate([
            'product_id'     => 'required|integer',
            'total_qty'      => 'required|integer',
            'total_amount'   => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        $id =  DB::table('tb_items')->insertGetId([
            'product_id'     => $request->product_id,
            'total_qty'      => $request->total_qty,
            'total_amount'   => $request->total_amount,
            'payment_method' => $request->payment_method,
            'create_at'      => now(),
            'update_at'      => now(),
        ]);

        return response()->json([
            'message' => 'Order created successfully',
            'id'      => $id    
        ], 201);
    }
    /**
     * Display the specified resource.
     */
       public function show($id)
    {
        $order = DB::table('tb_items')
        ->Join('tb_productnew', 'tb_items.product_id', '=', 'tb_productnew.id')
        ->join('tb_categories','tb_productnew.category','=','tb_categories.id')
        ->select(
            'tb_items.*',
            'tb_productnew.product_namekhmer',
            'tb_productnew.product_nameenglish',
            'tb_productnew.price',
            'tb_productnew.stock',
            'tb_productnew.category',
            'tb_categories.category_name',
            'tb_categories.description',
            'tb_categories.image',
            'tb_categories.image_public_id' 
             )->where('tb_items.id', $id)->get();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $updated = DB::table('tb_items')
            ->where('id',$id)
            ->update([
                'product_id'     => $request->product_id,
                'total_qty'      => $request->total_qty,
                'total_amount'   => $request->total_amount,
                'payment_method' => $request->payment_method,
                'update_at'      => now(),
            ]);

        if (!$updated) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
    {
        $deleted = DB::table('tb_items')->where('id', $id)->delete();

        if (!$deleted) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
