<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Store photos.
     */
    public static function store(Request $request, $diskname, $file)
    {
        $filename = $file->hashName();
        // dd($file, $path, $filename);
        $type = $request->type;

        // dd($storedFile);
        if (Storage::disk($diskname)->put($filename, $file->getContent())) {
            $addedFile = File::create([
                'path' => $filename,
            ]);

            return $addedFile->id;
        }

        return null;
    }

    /**
     * Show the photo.
     */
    public function show($id)
    {
        // Find the photo by ID
        $photo = File::findOrFail($id);

        // Return the view with the photo
        return view('photos.show', compact('photo'));
    }
}
