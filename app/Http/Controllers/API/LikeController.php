<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
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
        $like = new Like();
        $like->blog_id = $id;
        $like->user_id = Auth::id();
        $like->save();
        $blog = Blog::find($id);
        $blog->likes_count++;
        $blog->save();

        $like = Like::find($like->id);

        return response()->json([
            'success' => true,
            'message' => 'like given to post successfully',
            'data' => $like,
        ], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $like = Blog::find($id)->likes()->where('user_id', Auth::id())->first();
        $like->delete();

        $blog = Blog::find($id);
        $blog->likes_count--;
        $blog->save();

        return response()->json([
            'success' => false,
            'message' => 'like deleted successfully',
            'data' => $like,
        ], 200);
    }

    public function like_check($id)
    {
        $like = Blog::find($id)->likes()->where('user_id', Auth::id())->first();
        if (is_null($like)) 
        {
            return response()->json([
                'success' => false,
                'message' => 'No like in this post',
                'data' => $like,
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Like has in this post',
            'data' => $like,
        ], 200);
    }

}
