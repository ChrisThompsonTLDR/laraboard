<?php

namespace Christhompsontldr\Laraboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Christhompsontldr\Laraboard\Models\Post;
use Christhompsontldr\Laraboard\Models\Thread;

class PostController extends Controller
{
    public function edit(Post $post)
    {
        $this->authorize('laraboard::post-edit', $post);

        if ($post->type != 'Thread') {
            $thread = Thread::find($post->parent_id);
        } else {
            $thread = $post;
        }

        return view('laraboard::post.edit', compact('post', 'thread'));
    }

    /**
     *
     */
    public function update(Request $request, $post)
    {
        $this->authorize('laraboard::post-edit', $post);

        $this->validate($request, [
            'body' => 'required|max:4000',
        ]);

        $post->body = $request->body;

        if (!$post->save()) {
            return back()
                ->withInput()
                ->with('error', 'Failed to edit post.');
        }

        return redirect()->route('thread.show', $post->route)->with('success', 'Post updated.');
    }

    public function delete(Post $post)
    {
        $thread = $post->thread;

        $this->authorize('laraboard::post-delete', $post);

        $post->status = 'Deleted';
        $post->save();
        $post->delete();

        if ($post->type == 'Thread') {
            return redirect()->route('forum.index')->with('success', 'Reply deleted.');
        }

        return redirect()->route('thread.show', $thread->lastPageRoute)->with('success', 'Reply deleted.');
    }
}
