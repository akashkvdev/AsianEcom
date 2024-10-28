<?php
namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class TempImageController extends Controller
{
    public function create(Request $request)
    {
        // Check if an image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image'); // Get the uploaded file
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            // Save the image in the TempImage model
            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            // Move the original image to the temp directory
            $image->move(public_path('temp'), $newName);

            // Generate thumbnail
            $sourcePath = public_path('temp/' . $newName);
            $destPath = public_path('temp/thumb/');

            // Check if thumbnail directory exists; create if not
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true); // Create the directory with permissions
            }

            $thumbnailPath = $destPath . $newName;
            $img = Image::make($sourcePath);
            $img->fit(300, 275);
            $img->save($thumbnailPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('temp/thumb/' . $newName),
                'message' => 'Image uploaded successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No image uploaded'
        ], 400); // Return 400 Bad Request if no image is provided
    }
}
