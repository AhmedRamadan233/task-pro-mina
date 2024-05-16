<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::with('pictures')->get();
        return view('dashboard.pages.albums.index' , compact('albums'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $album = Album::create([
            'name' => $request->name,
        ]);
        return response()->json([
            'album' => $album,
            'message' => 'Album created successfully.',
        ], 201);
    }

    public function edit($id)
    {
        $editAlbum = Album::findOrFail($id);
        return response()->json(['editAlbum' => $editAlbum]);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $album = Album::findOrFail($id);
        $rules = [
            'name' => 'required|string|max:255',
        ];
        $validatedData = $request->validate($rules);
        $album->name = $validatedData['name']; 
        $update = $album->save();

        if ($update) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function destroy(Request $request, Album $album)
    {
        // Check if the album has pictures
        if ($album->pictures->isNotEmpty()) {
            // If the album is not empty, return a response with choices
            return response()->json([
                'message' => 'Album is not empty. Choose an action.',
                'choices' => [
                    'delete_all' => 'Delete all pictures in the album',
                    'move_pictures' => 'Move pictures to another album'
                ]
            ]);
        }else{
            $album->delete();
            return response()->json(['message' => 'Album deleted successfully']);
        }
    }


    public function deleteAllPictures(Album $album)
    {
        // Delete all pictures associated with the album
        $album->pictures()->delete();
        $album->delete();
        return response()->json(['message' => 'All pictures deleted successfully']);
    }


    public function movePicturesToAnotherAlbum(Request $request, Album $album)
    {
        $request->validate([
            'target_album_id' => 'required|exists:albums,id',
        ]);

        $targetAlbumId = $request->input('target_album_id');
        $targetAlbum = Album::findOrFail($targetAlbumId);
        $album->pictures()->update(['album_id' => $targetAlbum->id]);

        return response()->json(['message' => 'Pictures moved to another album successfully']);
    }
}
