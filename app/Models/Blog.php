<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $guarded = [];
    protected $table = "blogs";
    protected $with = ['user', 'tags'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag', 'blog_id', 'tag_id');
    }
}
