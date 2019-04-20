<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    public function store()
    {
        request()->validate([
            'image' => 'required|image'
        ]);

        return request()->file('image')->store('images', 'public');
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
            Storage::disk('public')->delete('images/' . substr($name, strrpos($name, '/') + 1));
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Image deleted'
        ]);
    }
}
