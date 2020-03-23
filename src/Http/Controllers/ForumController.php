<?php

namespace Christhompsontldr\Laraboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Christhompsontldr\Laraboard\Models\Post;
use Christhompsontldr\Laraboard\Models\Category;

class ForumController extends Controller
{
    /**
    * Home page of the forums, displays all categories
    *
    */
    public function index()
    {
        $categories = Category::get();

        return view('laraboard::forum.index', compact('categories'));
    }

    public function search(Request $request, $term = null)
    {
        if (!$term && $request->has('term')) {
            return redirect()->route('forum.search', $request->input('term'));
        }

        $posts = Post::search($term)->paginate(config('laraboard.post.limit', 15));

        return view('laraboard::forum.search', compact('posts', 'term'));
    }
}
