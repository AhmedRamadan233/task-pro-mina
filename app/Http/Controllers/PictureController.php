<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PictureController extends Controller
{
    public function index()
    {
        $pictures = Picture::with('album')->get();
        $albums = Album::with('pictures')->get();
        return view('dashboard.pages.pictures.index', compact('pictures', 'albums'));
    }



    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'album_id' => 'required|exists:albums,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate file input
        ]);

        $file = $request->file('image');
        $name = Str::random(10);
        $url = Storage::putFileAs('images', $file, $name . '.' . $file->extension());
        $url = Storage::disk('public')->putFileAs('images', $file, $name . '.' . $file->extension());

        $picture = Picture::create([
            'name' => $validatedData['name'],
            'album_id' => $validatedData['album_id'],
            'image' => env('APP_URL') . '/' . $url,
        ]);

        return response()->json([
            'picture' => $picture,
            'message' => 'Picture created successfully.',
        ], 201);
    }


    public function edit($id)
    {
        $editPicture = Picture::with('album')->findOrFail($id);
        return response()->json(['editPicture' => $editPicture]);
    }


    // public function update(Request $request)
    // {
    //     $id = $request->input('id');
    //     $picture = Picture::findOrFail($id);
    //     $rules = [
    //         'name' => 'required|string|max:255',
    //         'album_id' => 'required|exists:albums,id',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ];
    //     $validatedData = $request->validate($rules);
    //     $file = $request->file('image');
    //     $name = Str::random(10);
    //     $url = Storage::putFileAs('images', $file, $name . '.' . $file->extension());

    //     $picture->name = $validatedData['name'];
    //     $picture->album_id = $validatedData['album_id'];
    //     $picture->image = env('APP_URL') . '/' . $url;
    //     $picture->save();

    //     if ($picture) {
    //         return response()->json(['success' => true]);
    //     } else {
    //         return response()->json(['success' => false]);
    //     }
    // }


    public function update(Request $request)
    {

        $id = $request->input('id');
        $picture = Picture::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'album_id' => 'required|exists:albums,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if an image file is uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = Str::random(10);
            $url = Storage::putFileAs('images', $file, $name . '.' . $file->extension());
            $url = Storage::disk('public')->putFileAs('images', $file, $name . '.' . $file->extension());
            $picture->update(['image' => env('APP_URL') . $url]);
        }

        // Update other fields
        $picture->update([
            'name' => $validatedData['name'],
            'album_id' => $validatedData['album_id'],
        ]);

        // Return a response or redirect
        if ($picture->wasChanged()) {
            return response()->json(['success' => true, 'message' => 'Picture updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No changes detected or update failed.']);
        }
    }



    public function destroy(Request $request, Picture $picture)
    {
        $picture->delete();

        return response()->json(['message' => 'picture deleted successfully']);
    }
}
