<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Response;
use Inertia\ResponseFactory;

class PostController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $postsQuery = Post::query();

        if($tag = request('tag')){
            $postsQuery->whereJsonContains('tags',$tag);
        }

        $posts = $postsQuery->orderBy('id')->get();

        return inertia('Posts/Index', compact('posts','tag'));
    }

    public function show(Post $post): Response|ResponseFactory
    {
        return inertia('Posts/Show', compact('post'));
    }
}
