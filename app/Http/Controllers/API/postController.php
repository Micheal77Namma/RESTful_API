<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\post;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\API\baseController as baseController;
use App\Http\Controllers\Resources\post as postResources;
use Illuminate\Support\Facades\Auth;


class postController extends baseController
{
    public function index()
    {
        $posts = Post::all();
        return $this->sendResponse(postResources::collection($posts),'All posts sent');
    }

    public function userPosts($id)
    {
        $posts = Post::where('user_id', $id)->get;
        return $this->sendResponse(postResources::collection($posts),'posts sent successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please Validate error', $validator->errors());
        }
        $user = Auth::user();
        $input['user_id'] = $user->id;
        $post = Post::create($input);
        return $this->sendResponse(new postResources($post), 'Post created successfully');
    }


    public function show($id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError('Post not found');
        }
        return $this->sendResponse(new postResources($post), 'Post found successfully');
    }


    public function update(Request $request, post $post)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please Validate error', $validator->errors());
        }

        if ($post->user_id != Auth::id()) {
            return $this->sendError('you do not have rights', $validator->errors());
        }

        $post->title = $input['title'];
        $post->description = $input['description'];
        $post->save();
        return $this->sendResponse(new postResources($post), 'Post updated successfully');
    }


    public function destroy(post $post)
    {
        $errorMessage = [];
        if ($post->user_id != Auth::id()) {
            return $this->sendError('you do not have rights', $errorMessage);
        }

        $post->delete();
        return $this->sendResponse(new postResources($post), 'Post deleted successfully');
    }
}
