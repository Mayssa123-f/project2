<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PassengerImageController extends Controller
{
    public function upload(Request $request, $id)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048']
        ]);

        $passenger = Passenger::findOrFail($id);
        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();

        $originalPath = public_path('passenger/original/');
        $thumbnailPath = public_path('passenger/thumbnail/');

        if (!file_exists($originalPath)) {
            mkdir($originalPath, 0755, true);
        }
        if (!file_exists($thumbnailPath)) {
            mkdir($thumbnailPath, 0755, true);
        }

        $file->move($originalPath, $filename);

        $thumbnail = Image::make($originalPath . $filename)
            ->fit(150, 150, function ($constraint) {
                $constraint->upsize();
            });

        $tempThumbnailPath = public_path('temp/' . $filename);
        if (!file_exists(public_path('temp/'))) {
            mkdir(public_path('temp/'), 0755, true);
        }


        $thumbnail->save($tempThumbnailPath);
        $s3ThumbnailPath = 'passenger/thumbnail/' . $filename;
        Storage::disk('s3')->put($s3ThumbnailPath, file_get_contents($tempThumbnailPath), 'public');

        unlink($tempThumbnailPath);

        $passenger->image = 'passenger/original/' . $filename;
        $passenger->thumbnail = Storage::disk('s3')->url($s3ThumbnailPath);
        $passenger->save();

        return response([
            'success' => true,
            'original_url' => asset($passenger->image),
            'thumbnail_url' => asset($passenger->thumbnail),
        ], 200);
    }
}
