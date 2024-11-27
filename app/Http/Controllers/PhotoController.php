<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Get all photos associated with a specific comment.
     *
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function getPhotosByComment($commentId)
    {
        $photos = Photo::where('comment_id', $commentId)->get();

        return response()->json($photos);
    }

    /**
     * Store photos associated with a comment.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $commentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function storePhotos($comment, $photos)
    {
        $counter = 0;

        foreach ($photos as $photo) {
            // Generate a unique filename for each photo
            $filename = time() . '_' . $comment->id . '_'. $counter;

            $counter++;
            
            // Move the photo to the 'images/others' directory
            $photo->move(public_path('images/others'), $filename);

            // Store the photo in the database and associate it with the comment
            Photo::create([
                'comment_id' => $comment->id,  // Associate the photo with the comment
                'path' => $filename,  // Store the relative path
            ]);
        }
    }

    /**
     * Show the photo.
     */
    public function show($id)
    {
        // Find the photo by ID
        $photo = Photo::findOrFail($id);
        
        // Return the view with the photo
        return view('photos.show', compact('photo'));
    }
}
