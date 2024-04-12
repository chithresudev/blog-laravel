<?php

namespace App\Http\Controllers;

use Storage;
use Carbon\Carbon;
use App\Models\Post;

use Illuminate\Http\Request;

class NewPostController extends Controller
{
       
    /**
     * Update the specified resource in storage.
     */
    public function updateNew(Request $request, Post $post)
    {

        if($request->hasFile('path')) {
            $file = $request->path;
            $currentDate = Carbon::now()->format('YmdHs');
            $filename = 'blog_' . $currentDate . '.' . $file->getClientOriginalExtension();
            
            Storage::disk('uploadImage')->put($filename,  File::get($file));
           
        }

        $post->name = $request->name;
        $post->description = $request->description;
        $post->save();

        return response()->json(['status' => 'success', 'message' => 'Blog post hasbeen created.']);
    
    }

}
