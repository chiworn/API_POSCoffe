<?php

namespace App\Http\Controllers;

use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $image = DB::table('TB_image')->get();
        return response()->json([
            "Message" => "GET Image Success",
            "Data"    => $image
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)    
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        // Cloudinary config
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true],
        ]);

        // Upload image
        $upload = $cloudinary->uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'product_images']
        );

        // Save only image + created_at
        DB::table('TB_image')->insert([
            'Proimage'       => $upload['secure_url'],
            'image_public_id' => $upload['public_id'],
            'created_at'  => now(),
        ]);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'image'   => $upload['secure_url'],
        ], 201);
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
    public function update(Request $request, $id)
{
    $request->validate([
        'image' => 'required|image|max:2048',
    ]);

    // 1️⃣ Get old image from DB
    $oldImage = DB::table('TB_image')->where('id', $id)->first();

    if (!$oldImage) {
        return response()->json([
            'message' => 'Image not found'
        ], 404);
    }

    // 2️⃣ Cloudinary config
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key'    => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
        ],
        'url' => ['secure' => true],
    ]);

    // 3️⃣ Delete old image from Cloudinary
    if ($oldImage->image_public_id) {
        try {
            $cloudinary->uploadApi()->destroy($oldImage->image_public_id);
        } catch (\Exception $e) {
            // optional: log error
        }
    }

    // 4️⃣ Upload new image
    $upload = $cloudinary->uploadApi()->upload(
        $request->file('image')->getRealPath(),
        ['folder' => 'product_images']
    );

    // 5️⃣ Update DB
    DB::table('TB_image')->where('id', $id)->update([
        'Proimage'        => $upload['secure_url'],   // ✅ correct column
        'image_public_id' => $upload['public_id'],
        'update_at'       => now(),                   // ✅ your column
    ]);

    return response()->json([
        'message' => 'Image updated successfully',
        'image'   => $upload['secure_url'],
    ], 200);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
