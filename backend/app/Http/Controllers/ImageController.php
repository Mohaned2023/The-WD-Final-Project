<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ImageController
{
    public function upload(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max 2MB
        ]);
        $imagePath = $request->file('image')->store('images', 'public');
        DB::table('images')->insert([
            'user_id' => $request['user']['id'],
            'path' => $imagePath
        ]);
        return response()->json(['message' => 'Image uploaded successfully!']);
    }
}
