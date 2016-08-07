<?php

namespace Christhompsontldr\Laraboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;

use Christhompsontldr\Laraboard\Models\Category;
use Christhompsontldr\Laraboard\Models\Post;

class CategoryController extends Controller
{

    /**
    * Category creation form
    *
    */
    public function create()
    {
        $this->authorize('laraboard::category-create');

        return view('laraboard::category.create');
    }

    /**
    * put your comment there...
    *
    * @param Request $request
    * @return {\Illuminate\Http\RedirectResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\RedirectResponse}
    */
    public function store(Request $request)
    {
        $this->authorize('laraboard::category-create');

        $this->validate($request, [
            'name' => 'required|max:255',
            'body' => 'max:255'
        ]);

        //  category names must be unique
        if (Category::whereName(strip_tags($request->name))->count() > 0) {
            return redirect()->back()->withInput()->with('danger', 'Category names must be unique.');
        }

        $category          = new Post;
        $category->name    = $request->name;
        $category->body    = $request->body;
        $category->type    = 'Category';
        $category->user_id = \Auth::user()->id;
        $category->save();

        return redirect()->route('forum.index')->with('success', 'Category created successfully.');
    }

    public function show($slug)
    {
        $category = Category::whereSlug($slug)->firstOrFail();

        return view('laraboard::category.show', compact('category'));
    }

    public function edit($slug)
    {
        $category = Category::whereSlug($slug)->firstOrFail();

        $this->authorize('laraboard::category-edit', $category);

        return view('laraboard::category.edit', compact('category'));
    }

    public function update(Request $request, $slug)
    {
        $category = Category::whereSlug($slug)->firstOrFail();

        $this->authorize('laraboard::category-edit', $category);

        $this->validate($request, [
            'name' => 'required|max:255',
            'body' => 'max:255'
        ]);

        //  category names must be unique
        if (Category::whereName(strip_tags($request->name))->where('id', '!=', $category->id)->count() > 0) {
            return redirect()->back()->withInput()->with('danger', 'Category names must be unique.');
        }

        $category->name    = $request->name;
        $category->body    = $request->body;
        $category->user_id = \Auth::user()->id;
        $category->save();

        return redirect()->route('forum.index')->with('success', 'Category updated successfully.');
    }

    /**
    * Handles moving the category up or down in the list of categories.
    *
    * @param mixed $slug
    * @param mixed $direction
    * @return {\Illuminate\Http\RedirectResponse|\Illuminate\Http\RedirectResponse}
    */
    public function reposition($slug, $direction)
    {
        $this->authorize('laraboard::category-manage');

        $category = Category::whereSlug($slug)->firstOrFail();

        //  move up
        if ($direction == 'up') {
            $category->moveLeft();
        }
        //  move down
        else {
            $category->moveRight();
        }

        return redirect()->back()->with('success', 'Category successfully moved.');
    }
}
