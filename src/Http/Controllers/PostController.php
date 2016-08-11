<?php

namespace Christhompsontldr\Laraboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Christhompsontldr\Laraboard\Models\Post;

class PostController extends Controller
{
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('laraboard::post-edit', $post);

        return view('laraboard::post.edit', compact('post'));
    }

    /**
     *
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('laraboard::post-edit', $post);

        $this->validate($request, [
            'body' => 'required|max:4000',
        ]);

        $post->body = $request->body;
        $post->save();

        return redirect()->route('thread.show', [$post->thread->board->category->slug, $post->thread->board->slug, $post->thread->slug, $post->thread->name_slug])->with('success', 'Post updated.');
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('laraboard::post-delete', $post);

        $post->status = 'Deleted';
        $post->save();
        $post->delete();

        if ($post->type == 'Thread') {
            return redirect()->route('forum.index')->with('success', 'Reply deleted.');
        }

        return redirect()->back()->with('success', 'Reply deleted.');
    }
}