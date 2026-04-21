<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json([
            'message' => 'Get data success',
            'datas'   => $products
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $cloudinary = $this->getCloudinary();
            $upload = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'products']
            );
            $data['image'] = $upload['secure_url'];
            $data['image_public_id'] = $upload['public_id'];
        }

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product Add Success',
            'image'   => $product->image
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $cloudinary = $this->getCloudinary();

            // Delete old image
            if ($product->image_public_id) {
                try {
                    $cloudinary->uploadApi()->destroy($product->image_public_id);
                } catch (\Exception $e) {
                    // Log error or ignore
                }
            }

            // Upload new image
            $upload = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'products']
            );
            $data['image'] = $upload['secure_url'];
            $data['image_public_id'] = $upload['public_id'];
        }

        $product->update($data);

        return response()->json([
            'message' => 'Product updated successfully',
            'data'    => $product->fresh('category')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($product->image_public_id) {
            $cloudinary = $this->getCloudinary();
            $cloudinary->uploadApi()->destroy($product->image_public_id);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product & image deleted successfully'
        ], 200);
    }

    private function getCloudinary()
    {
        return new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true]
        ]);
    }
}
