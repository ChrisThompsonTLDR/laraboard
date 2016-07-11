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
	 * Show the main index page of the forum
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$categories = Category::all();

		return view('laraboard::forum.index', compact('categories'));
	}

    public function create()
    {
        return view('laraboard::forum.create');
    }

    public function store(Request $request)
    {
        $this->authorize('forum-create');

        $this->validate($request, [
            'name' => 'required|max:255',
            'body' => 'required|max:255'
        ]);

        $board          = new Post;
        $board->name    = $request->name;
        $board->body    = $request->body;
        $board->type    = 'Category';
        $board->slug    = $board->createSlug();
        $board->user_id = \Auth::user()->id;
        $board->save();
//        $board->makeChildOf($category);

        return redirect()->route('forum.index')->with('success', 'Forum created successfully.');
    }
}
