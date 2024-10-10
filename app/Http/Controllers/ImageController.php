<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        Storage::delete($image->path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}
