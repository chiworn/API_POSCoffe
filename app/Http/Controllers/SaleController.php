<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\SellingProduct;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = SellingProduct::with(['cashier', 'item.product.category'])->get();
        
        $result = $sales->map(function ($sale) {
            return [
                'id' => $sale->id,
                'created_at' => $sale->created_at,
                'cashier' => [
                    'name'  => $sale->cashier->name ?? null,
                    'email' => $sale->cashier->email ?? null,
                ],
                'item' => [
                    'product_id'     => $sale->item->product_id ?? null,
                    'total_qty'      => $sale->item->total_qty ?? null,
                    'total_amount'   => $sale->item->total_amount ?? null,
                    'payment_method' => $sale->item->payment_method ?? null,
                    'product' => [
                        'product_namekhmer'   => $sale->item->product->product_namekhmer ?? null,
                        'product_nameenglish' => $sale->item->product->product_nameenglish ?? null,
                        'price'               => $sale->item->product->price ?? null,
                        'stock'               => $sale->item->product->stock ?? null,
                        'category' => [
                            'id'            => $sale->item->product->category->id ?? null,
                            'category_name' => $sale->item->product->category->category_name ?? null,
                            'description'   => $sale->item->product->category->description ?? null,
                        ],
                        'image'           => $sale->item->product->image ?? null,
                        'image_public_id' => $sale->item->product->image_public_id ?? null,
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
    public function store(SaleRequest $request)
    {
        $data = $request->validated();

        foreach ($data['items_id'] as $itemId) {
            SellingProduct::create([
                'cashier_id' => $data['cashier_id'],
                'items_id'   => $itemId,
            ]);
        }

        return response()->json([
            'Message' => 'Insert Data Success'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sale = SellingProduct::with(['cashier', 'item.product.category'])->find($id);
        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }

        $result = [
            'id' => $sale->id,
            'created_at' => $sale->created_at,
            'cashier' => [
                'name'  => $sale->cashier->name ?? null,
                'email' => $sale->cashier->email ?? null,
            ],
            'item' => [
                'product_id'     => $sale->item->product_id ?? null,
                'total_qty'      => $sale->item->total_qty ?? null,
                'total_amount'   => $sale->item->total_amount ?? null,
                'payment_method' => $sale->item->payment_method ?? null,
                'product' => [
                    'product_namekhmer'   => $sale->item->product->product_namekhmer ?? null,
                    'product_nameenglish' => $sale->item->product->product_nameenglish ?? null,
                    'price'               => $sale->item->product->price ?? null,
                    'stock'               => $sale->item->product->stock ?? null,
                    'category' => [
                        'id'            => $sale->item->product->category->id ?? null,
                        'category_name' => $sale->item->product->category->category_name ?? null,
                        'description'   => $sale->item->product->category->description ?? null,
                    ],
                    'image'           => $sale->item->product->image ?? null,
                    'image_public_id' => $sale->item->product->image_public_id ?? null,
                ]
            ]
        ];

        return response()->json([
            'message' => "Get Data Saller Success",
            'Data'    => $result
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaleRequest $request, string $id)
    {
        $sale = SellingProduct::find($id);
        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }

        $sale->update($request->validated());

        return response()->json([
            'message' => 'Update successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale = SellingProduct::find($id);
        if (!$sale) {
            return response()->json(['Message' => 'Sale not found'], 404);
        }

        $sale->delete();

        return response()->json([
            'Message' => 'Delete success'
        ]);
    }
}
