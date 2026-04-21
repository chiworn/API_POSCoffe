<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with(['product.category'])->get();
        return response()->json([
            'Message' => 'Get Items successs',
            'Data'    => $items
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        $item = Item::create($request->validated());

        return response()->json([
            'message' => 'Order created successfully',
            'id'      => $item->id
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = Item::with(['product.category'])->find($id);

        if (!$item) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemRequest $request, $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $item->update($request->validated());

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
