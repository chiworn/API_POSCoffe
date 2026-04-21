<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::with('glass')->get();
        return response()->json([
            'Message' => "get Stock sucess",
            "data"    => $stocks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockRequest $request)
    {
        $stock = Stock::create($request->validated());

        return response()->json(['message' => 'Stock added', 'id' => $stock->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stock = Stock::with('glass')->find($id);
        if (!$stock) {
            return response()->json(['error' => 'Stock not found'], 404);
        }
        return response()->json($stock);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StockRequest $request, $id)
    {
        $stock = Stock::find($id);
        if (!$stock) {
            return response()->json(['error' => 'Stock not found'], 404);
        }

        $stock->update($request->validated());

        return response()->json(['message' => 'Stock updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stock = Stock::find($id);
        if (!$stock) {
            return response()->json(['error' => 'Stock not found'], 404);
        }

        $stock->delete();

        return response()->json(['message' => 'Stock deleted']);
    }
}
