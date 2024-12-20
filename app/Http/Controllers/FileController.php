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
    public static function store(Request $request, $diskname, $file = null, $commentId = null)
    {
        $file = $file ?? $request->file;
        $filename = $file->hashName();

        if (Storage::disk($diskname)->put($filename, $file->getContent())) {
            $addedFile = File::create(['path' => $filename, 'comment_id' => $commentId]);

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
