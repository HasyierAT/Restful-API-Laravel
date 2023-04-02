<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(2);
        return response()->json([
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $userId =  ['data' => $user->id];

            $category = Category::find(1);
            $categoryId = ['data' => $category->id];

            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $request->image,
                'user_id' => implode($userId),
                'category_id' => implode($categoryId),
            ]);

            return response(['data' => $post], 200);
        } else {
            return response(['data' => "Fail"], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }

        return response()->json([
            'data' => $post
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $post = Post::find($id);
        $post->title = $input['title'];
        $post->content = $input['content'];
        $post->save();

        return response()->json([
            'data' => $post
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ], 200);
    }
}
