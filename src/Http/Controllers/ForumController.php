<?php
/**
 * This file is part of LaraBB.
 *
 * (c) Jason Clemons <jason@larabb.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * that was distributed with this source code.
 */

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
