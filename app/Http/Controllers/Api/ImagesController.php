<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImagesController extends Controller
{
    public function store()
    {
        request()->validate([
            'image' => 'required|image'
        ]);

        $photo = Image::make(request()->file('image')->getRealPath())
                      ->resize(1200, null, function ($constraint) {
                          $constraint->aspectRatio();
                      })
                      ->encode('jpg');

        Storage::put($name = request()->file('image')->hashName(), $photo);

        return $name;
    }

    /**
     * @throws \Exception
     */
    public function destroy()
    {
        request()->validate([
            'name' => 'required|array'
        ]);

        foreach (request('name') as $name) {
            Storage::disk('images')->delete($name);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Image deleted'
        ]);
    }
}
