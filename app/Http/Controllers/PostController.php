<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostDetailResorce;

class PostController extends Controller
{
    public function index(){
        return  PostResource::collection(Post::all());
    }

    public function show($id){
        $post = Post::with('writter:id,username')->findOrFail($id);
        return  new PostDetailResorce($post);
    }
    public function show2($id){
        $post = Post::findOrFail($id);
        return  new PostDetailResorce($post);
    }
}
