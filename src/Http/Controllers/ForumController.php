<?php

namespace Christhompsontldr\Laraboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Gate;
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
        $categories = Category::all();

        return view('laraboard::forum.index', compact('categories'));
    }
}
