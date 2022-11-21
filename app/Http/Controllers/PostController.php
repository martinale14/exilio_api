<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Validators\TokenValidator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

        foreach ($posts as $post) {
            $post->user_name = User::where('id', '=', $post->user_id)->get()->first()->name;
            $post->comments_count = count(Comment::where('post_id', '=', $post->id)->get());
        }

        return response()->json($posts, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'userId' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 200);
        }

        $post = new Post();

        $post->content = $request->input('content');
        $post->user_id = $request->input('userId');
        $post->likes_count = 0;

        $post->save();

        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(int $postId)
    {
        $post = Post::where('id', '=', $postId)->get()->first();

        if($post == null) {
            return response()->json(['error'=>'El post no fue encontrado']);
        }

        $post->user_name = User::where('id', '=', $post->user_id)->get()->first()->name;
        $comments = Comment::where('post_id', '=', $post->id)->get();
        $post->comments_count = count($comments);
        $post->comments = $comments;

        foreach($post->comments as $comment){
            $comment->user_name = User::where('id', '=', $comment->user_id)->get()->first()->name;
        }

        return $post;
    }

    public function addLike(int $postId)
    {
        $post = Post::where('id', '=', $postId)->get()->first();

        if($post == null) {
            return response()->json(['error'=>'El post no fue encontrado']);
        }

        $post->likes_count = intval($post->likes_count) + 1;

        $post->update();

        return $post;
    }
}
