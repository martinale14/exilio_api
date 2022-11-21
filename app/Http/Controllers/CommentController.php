<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CommentController extends Controller
{
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
            'postId' => 'required|exists:posts,id',
            'userId' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 200);
        }

        $comment = new Comment();

        $comment->content = $request->input('content');
        $comment->user_id = $request->input('userId');
        $comment->post_id = $request->input('postId');

        $comment->save();

        return $comment;
    }
}
