<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashiersaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = DB::table('tb_selling_products')
        ->leftJoin('users','tb_selling_products.cashier_id','=','users.id')
        ->leftJoin('tb_items','tb_selling_products.items_id','=','tb_items.id')
        ->leftJoin('tb_productnew', 'tb_items.product_id', '=', 'tb_productnew.id')
        ->leftJoin('tb_categories','tb_productnew.category','=','tb_categories.id')
        ->select(
            'tb_selling_products.id',
            'tb_items.product_id',
            'tb_items.total_qty',
            'tb_items.total_amount',
            'tb_items.payment_method',
            'users.name',
            'users.email',
            'tb_productnew.product_namekhmer',
            'tb_productnew.product_nameenglish',
            'tb_productnew.price',
            'tb_productnew.stock',
            'tb_productnew.category',
            'tb_productnew.image',
            'tb_productnew.image_public_id',
            'tb_categories.category_name',
            'tb_categories.description as category_description',
            'tb_selling_products.created_at',
            'tb_selling_products.updated_at'
        )->get();
               $result = $rows->map(function ($row) {
            return [
                'id' => $row->id,
                'created_at' => $row->created_at,
                'cashier' => [
                    'name' => $row->name,
                    'email' => $row->email,
                ],
                'item' => [
                    'product_id' => $row->product_id,
                    'total_qty' => $row->total_qty,
                    'total_amount' => $row->total_amount,
                    'payment_method' => $row->payment_method,
                    'product' => [
                        'product_namekhmer' => $row->product_namekhmer,
                        'product_nameenglish' => $row->product_nameenglish,
                        'price' => $row->price,
                        'stock' => $row->stock,
                        'categorys' => [
                            'id' => $row->category,
                            'category_name' => $row->category_name,
                            'description' => $row->category_description,
                        ],
                        'image' => $row->image,
                        'image_public_id' => $row->image_public_id,
                    ]
                ]
            ];
        });
        return response()->json([
            'message' => "Get Data Saller Success",
            'Data'    => $result
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request -> validate([
            'cashier_id' => 'required|numeric',
            'items_id'   => 'required|array',
            'items_id.*' => 'integer',
        ]);
        // DB::table('TB_selling_products')->insert([
        //     'cashier_id' => $request->cashier_id,
        //     'items_id'   => $request->items_id,
        //     'created_at' => now(),
        //     'updated-at' => now()
        // ]);

    foreach ($request->items_id as $itemId) {
        DB::table('tb_selling_products')->insert([
            'cashier_id' => $request->cashier_id,
            'items_id'   => $itemId,        // ✅ ONE item per row
            'created_at' => now(),
            'updated-at' => now(),          // ✅ correct column
        ]);
    }
        return response()->json([
            'Message' => 'inser Data sucess'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         $rows = DB::table('tb_selling_products')
       ->leftJoin('users','tb_selling_products.cashier_id','=','users.id')
        ->leftJoin('tb_items','tb_selling_products.items_id','=','tb_items.id')
        ->leftJoin('tb_productnew', 'tb_items.product_id', '=', 'tb_productnew.id')
        ->leftJoin('tb_categories','tb_productnew.category','=','tb_categories.id')
        ->select(
            'tb_selling_products.id',
            'tb_items.product_id',
            'tb_items.total_qty',
            'tb_items.total_amount',
            'tb_items.payment_method',
            'users.name',
            'users.email',
            'tb_productnew.product_namekhmer',
            'tb_productnew.product_nameenglish',
            'tb_productnew.price',
            'tb_productnew.stock',
            'tb_productnew.category',
            'tb_productnew.image',
            'tb_productnew.image_public_id',
            'tb_categories.category_name',
            'tb_categories.description as category_description',
            'tb_selling_products.created_at',
            'tb_selling_products.updated_at'
        )->where('tb_selling_products.id',$id)->get();
        $result = $rows->map(function ($row) {
            return [
                'id' => $row->id,
                'created_at' => $row->created_at,
                'cashier' => [
                    'name' => $row->name,
                    'email' => $row->email,
                ],
                'item' => [
                    'product_id' => $row->product_id,
                    'total_qty' => $row->total_qty,
                    'total_amount' => $row->total_amount,
                    'payment_method' => $row->payment_method,
                    'product' => [
                        'product_namekhmer' => $row->product_namekhmer,
                        'product_nameenglish' => $row->product_nameenglish,
                        'price' => $row->price,
                        'stock' => $row->stock,
                        'categorys' => [
                            'id' => $row->category,
                            'category_name' => $row->category_name,
                            'description' => $row->category_description,
                        ],
                        'image' => $row->image,
                        'image_public_id' => $row->image_public_id,
                    ]
                ]
            ];
        });
        return response()->json([
            'message' => "Get Data Saller Success",
            'Data'    => $result
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update = DB::table('tb_selling_products')
        ->where('id',$id)
        ->update([
            'cashier_id' => $request->cashier_id,
            'items_id'   => $request->items_id,
            'created_at' => now(),
            'updated-at' => now()

        ]);
        if(!$update){
            return response()->json([
                'message' => 'Not found !!'
            ]);
        }
        return response()->json([
            'message' => 'Update successfully '
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destory(string $id)
    {
        DB::table('tb_selling_products')->where('id',$id)->delete();
        return response()-> json([
            'Message' =>  'Delete success'
        ]);
    }
}
