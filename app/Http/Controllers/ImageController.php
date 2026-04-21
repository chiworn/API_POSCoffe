<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Models\Image;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = Image::all();
        return response()->json($images);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImageRequest $request)
    {
        $cloudinary = $this->getCloudinary();
        $upload = $cloudinary->uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'images']
        );

        $image = Image::create([
            'image_url' => $upload['secure_url'],
            'public_id' => $upload['public_id'],
        ]);

        return response()->json([
            'message'   => 'Image Add Success',
            'image_url' => $image->image_url
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ImageRequest $request, $id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $cloudinary = $this->getCloudinary();

        // Delete old image
        if ($image->public_id) {
            try {
                $cloudinary->uploadApi()->destroy($image->public_id);
            } catch (\Exception $e) {
                // Ignore
            }
        }

        // Upload new image
        $upload = $cloudinary->uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'images']
        );

        $image->update([
            'image_url' => $upload['secure_url'],
            'public_id' => $upload['public_id'],
        ]);

        return response()->json([
            'message'   => 'Image updated successfully',
            'image_url' => $image->image_url
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        if ($image->public_id) {
            $cloudinary = $this->getCloudinary();
            $cloudinary->uploadApi()->destroy($image->public_id);
        }

        $image->delete();

        return response()->json([
            'message' => 'Image deleted successfully'
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
