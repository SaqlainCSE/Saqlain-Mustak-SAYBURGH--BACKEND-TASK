<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $comment = new Comment();
        $comment->blog_id = $id;
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        $comment->save();

        $comment = Comment::find($comment->id);

        return response()->json([
            'success' => true,
            'message' => 'comment given to post successfully',
            'data' => $comment,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comment = Blog::find($id)->comments()->where('user_id', Auth::id())->first();
        $new_comment=$request->all();
        $comment->update($new_comment);
        return response()->json([
            'success' => true,
            'message' => 'comment updated',
            'data' => $comment,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Blog::find($id)->comments()->where('user_id', Auth::id())->first();
        $comment->delete();

        return response()->json([
            'success' => false,
            'message' => 'comment deleted successfully',
            'data' => $comment,
        ], 200);
    }
}
