<?php

namespace App\Http\Controllers;

use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function index()
    {
        $allproduct = DB::table('TB_Products')
        ->join('TB_categories','TB_Products.category','=','TB_categories.id')
        ->select(
            'TB_Products.*',
            'TB_categories.category_name',
            'TB_categories.description'
        )
        ->get();
        return response()->json([
            'message' => 'Get data success',
            'datas'   => $allproduct
        ],201);
    }
    public function store(Request $request)
    {
        $request ->validate([
            'product_namekhmer'   => 'required|string',
            'product_nameenglish' => 'required|string',
            'price'               => 'required|numeric',
            'stock'               => 'required',
            'status'              => 'required',
            'category'            => 'required',
            'image'               => 'required|image|max:2048',
        ]);

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true]
        ]);

        $upload = $cloudinary->uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'products']    
        );

        $imageUrl = $upload['secure_url'];
        $publicId  = $upload['public_id'];

        DB::table('TB_Products')->insert([
            'product_namekhmer'     => $request->product_namekhmer,
            'product_nameenglish'   => $request->product_nameenglish,
            'price'                 => $request->price,
            'stock'                 => $request->stock,
            'status'                => $request->status,
            'category'              => $request->category,
            'image'                 => $imageUrl,
            'image_public_id'       => $publicId, // ✅ SAVE THIS
            'create_at'             => now(),
            'update_at'             => now(),

        ]);
        return response()->json([
            'message' => 'Product Add Success',
            'image'   => $imageUrl
        ],201);
    }
    public function show(string $id)
    {
        //
    }

    //-------------Update--------------------//
   public function update(Request $request, $id)
{
    $request->validate([
        'product_namekhmer'   => 'required|string',
        'product_nameenglish' => 'required|string',
        'price'               => 'required|numeric',
        'stock'               => 'required',
        'status'              => 'required',
        'category'            => 'required',
        'image'               => 'nullable|image|max:2048', // Changed from 'required' to 'sometimes'
    ]);

    // 1️⃣ Get old product
    $product = DB::table('TB_Products')->where('id', $id)->first();

    if (!$product) {
        return response()->json([
            'message' => 'Product not found'
        ], 404);
    }

    $imageUrl = $product->image;
    $publicId = $product->image_public_id;

    // 2️⃣ If new image uploaded (now optional)
    if ($request->hasFile('image')) {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true]
        ]);

        // 🔥 Delete old image first
        if ($publicId) {
            try {
                $cloudinary->uploadApi()->destroy($publicId);
            } catch (\Exception $e) {
                // Log the error but continue with the update
                // Log::error('Failed to delete old image from Cloudinary: ' . $e->getMessage());
            }
        }

        // 🔥 Upload new image
        try {
            $upload = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'products']
            );

            $imageUrl = $upload['secure_url'];
            $publicId = $upload['public_id'];
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload image to Cloudinary',
                'error' => $e->getMessage()
            ], 500);
        }
     }

    // 3️⃣ Update DB
    $updated = DB::table('TB_Products')->where('id', $id)->update([
        'product_namekhmer'     => $request->product_namekhmer,
        'product_nameenglish'   => $request->product_nameenglish,
        'price'                 => $request->price,
        'stock'                 => $request->stock,
        'status'                => $request->status,
        'category'              => $request->category,
        'image'                 => $imageUrl,
        'image_public_id'       => $publicId,
        'update_at'             => now(),
    ]);

    if ($updated) {
        return response()->json([
            'message' => 'Product updated successfully',
            'data' => DB::table('TB_Products')->where('id', $id)->first()
        ], 200);
    }

    return response()->json([
        'message' => 'Failed to update product'
    ], 500);
}


    public function destroy($id)
    {
        $product = DB::table('TB_Products')->where('id', $id)->first();

    if (!$product) {
        return response()->json([
            'message' => 'Product not found'
        ], 404);
    }

    // 🔥 លុបរូបពី Cloudinary មុន
    if ($product->image_public_id) {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true]
        ]);

        $cloudinary->uploadApi()->destroy($product->image_public_id);
    }

    // 🔥 បន្ទាប់មកលុប data ក្នុង DB
    DB::table('TB_Products')->where('id', $id)->delete();

    return response()->json([
        'message' => 'Product & image deleted successfully'
    ], 200);
}

}
