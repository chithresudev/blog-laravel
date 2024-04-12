<?php

namespace App\Http\Controllers\Post;

use App\ResponseApi;
use Carbon\Carbon;
use File;
use Storage;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Like;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{

    use ResponseApi;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Post::get();
        return $this->sendResponse($post, 'Post retrived successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $validation = $request->validate([
        //     'name' => 'required|max:255',
        //     'description' => 'required',
        //     'path' => 'required'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'message' => 'Could not create a new user.',
        //         'errors' => $validation
        //     ], 400l);
        // }

        if ($request->hasFile('path')) {
            $file = $request->path;
            $currentDate = Carbon::now()->format('YmdHs');
            $filename = 'blog_' . $currentDate . '.' . $file->getClientOriginalExtension();

            Storage::disk('uploadImage')->put($filename,  File::get($file));

            Post::create([
                'path' => $filename,
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Blog post hasbeen created.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json(
            [
                'data' => $post,
                'comments' => $post->comments,
                'status' => 'success'
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'removed']);
    }

    public function postLike(Post $post)
    {

        $like = Like::create([
            'post_id' => $post->id,
            'like' => 1
        ]);



        return response()->json(['message' => 'liked', 'count' => optional($post->likes)->count()]);
    }

    public function comments(Request $request, Post $post)
    {

        $like = Comment::create([
            'post_id' => $post->id,
            'comments' => $request->comment
        ]);

        return response()->json(['message' => 'liked', 'post_comments' => $post->comments]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {

        $post->name = $request->name;
        $post->description = $request->description;

        if ($request->hasFile('path')) {
            $file = $request->path;
            $currentDate = Carbon::now()->format('YmdHs');
            $filename = 'blog_' . $currentDate . '.' . $file->getClientOriginalExtension();
            Storage::disk('uploadImage')->put($filename,  File::get($file));
            $post->path = $filename;
        }

        $post->save();

        return response()->json(['status' => 'success', 'message' => 'Blog post has been updated.']);
    }
}
