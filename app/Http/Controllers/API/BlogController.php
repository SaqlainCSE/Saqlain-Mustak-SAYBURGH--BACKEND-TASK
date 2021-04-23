<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        
        if ($request->has(['new']) && $request->input('new') == "true") 
        {
            $blogs = Blog::orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'New blog post fetched',
                'data' => $blogs,
            ], 200);
        }
        
        if ($request->has(['popular']) && $request->input('popular') == "true") 
        {
            $blogs = Blog::orderBy(DB::raw("`views_count` + `likes_count`"), 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Popular blog post fetched',
                'data' => $blogs,
            ], 200);
        }

        $blogs = Blog::orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'All blog post fetched',
            'data' => $blogs,
        ], 200);
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
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'content' => [],
        ]);

        if ($validator->fails()) 
        {
            return response()->json([
                'success' => false,
                'error' => $validator->errors(),
            ], 400);
        }

        $slug = Str::slug($request->title, '-') . '-' . (string) Str::uuid();

        $blog = new Blog();
        $blog->slug = $slug;
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->user_id = Auth::id();
        $blog->save();

        if ($request->tags) 
        {
            $tags = $request->tags;
            foreach ($tags as $tag) 
            {
                $new_tag = Tag::where('name', $tag)->first();
                if (is_null($new_tag)) 
                {
                    $new_tag = new Tag();
                    $new_tag->name = $tag;
                    $slug = Str::slug($tag, '-');
                    $new_tag->slug = $slug;
                    $new_tag->save();
                }
                $blog->tags()->attach($new_tag);
            }
        }

        $blog->save();

        $blog = Blog::find($blog->id);

        return response()->json([
            'success' => true,
            'message' => 'Blog posted successfully!!!',
            'data' => $blog,
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
        $blog = Blog::with(['likes', 'comments', 'user'])->find($id);

        if (is_null($blog)) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found',
            ], 404);
        }

        $blog->views_count++;
        $blog->save();
        return response()->json([
            'success' => true,
            'message' => 'Blog post with comments fetched',
            'data' => $blog,
        ], 200);
    }

    public function showBySlug($slug)
    {
        $blog = Blog::with(['likes', 'comments', 'user'])->where('slug', $slug)->first();
        //$blog = Blog::with(['likes', 'comments', 'user'])->find($id);
        if (is_null($blog)) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found',
            ], 404);
        }
        $blog->views_count++;
        $blog->save();
        return response()->json([
            'success' => true,
            'message' => 'Blog post with comments fetched',
            'data' => $blog,
        ], 200);
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
        
        $blog = Blog::find($id);

        if (is_null($blog)) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found',
            ], 404);
        }

        $blog->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Blog updated',
            'data' => $blog,
        ], 200);
    }

    // blogUpdated
    public function updateBlog(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'content' => ['required'],
        ]);

        if ($validator->fails()) 
        {
            return response()->json([
                'success' => false,
                'error' => $validator->errors(),
            ], 400);
        }
        
        $blog = Blog::with(['likes', 'comments', 'user'])->where('slug', $slug)->first();

        $blog->slug = $blog->slug;
        $blog->user_id = $blog->user_id;
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->tags()->detach();
        $blog->save();

        if ($request->tags)
        {
            $tags = $request->tags;
            foreach ($tags as $tag) 
            {
                $new_tag = Tag::where('name', $tag)->first();
                if (is_null($new_tag)) 
                {
                    $new_tag = new Tag();
                    $new_tag->name = $tag;
                    $slug = Str::slug($tag, '-');
                    $new_tag->slug = $slug;
                    $new_tag->save();
                }
                $blog->tags()->attach($new_tag);
            }
        }
        $blog->save();

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully!!!',
            'data' => $blog,
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
        $blog = Blog::find($id);

        if (is_null($blog)) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found',
            ], 404);
        }

        $blog->delete();
        return response()->json([
            'success' => true,
            'message' => 'Blog deleted',
            'data' => $blog,
        ], 200);
    }

    //Blog searching 
    public function search($title)
    {
        $search_blog=Blog::orderBy('id','desc')
                        ->where('title','like','%'.$title.'%')
                        ->orWhere('content','like','%'.$title.'%')
                        ->get();

        if (is_null($search_blog)) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Searching result not found',
            ], 404);
        }                        

        return response()->json([
            'success' => true,
            'message' => 'Searching result found',
            'data' => $search_blog,
        ], 200);

    }
}
