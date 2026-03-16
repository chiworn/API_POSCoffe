<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    protected $table = 'tb_categories'; // your table name

    // 🔹 Get all categories
    public function index()
    {
        $categories = DB::table($this->table)->get();
        return response()->json($categories);
    }

    // 🔹 Create new category
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description'   => 'nullable|string|max:255',
        ]);

        $id = DB::table($this->table)->insertGetId([
            'category_name' => $request->category_name,
            'description'   => $request->description,
            'created_at'    => now(),
        ]);

        return response()->json(['message' => 'Category created', 'id' => $id], 201);
    }

    // 🔹 Get a single category
    public function show($id)
    {
        $category = DB::table($this->table)->where('id', $id)->first();
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    // 🔹 Update category
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description'   => 'nullable|string|max:255',
        ]);

        $updated = DB::table($this->table)
            ->where('id', $id)
            ->update([
                'category_name' => $request->category_name,
                'description'   => $request->description,
                'created_at'    => now(),
            ]);

        if ($updated) {
            return response()->json(['message' => 'Category updated']);
        } else {
            return response()->json(['message' => 'Category not found or no changes'], 404);
        }
    }

    // 🔹 Delete category
    public function destroy($id)
    {
        $deleted = DB::table($this->table)->where('id', $id)->delete();
        if ($deleted) {
            return response()->json(['message' => 'Category deleted']);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
}
