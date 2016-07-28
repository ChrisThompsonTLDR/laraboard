<?php

namespace Christhompsontldr\Laraboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;

use Christhompsontldr\Laraboard\Models\Reply;
use Christhompsontldr\Laraboard\Models\Post;
use Christhompsontldr\Laraboard\Models\Thread;

class ReplyController extends Controller
{

    /**
     *
     */
    public function create()
    {
        return view('laraboard::replies.create');
    }

    /**
     *
     */
    public function store(Request $request, $slug)
    {
        $thread = Thread::whereSlug($slug)->firstOrFail();

        $this->authorize('laraboard::reply-create', $thread);

        $this->validate($request, [
            'body' => 'required|max:4000',
        ]);

        $reply            = new Post;
        $reply->body      = $request->body;
        $reply->user_id   = \Auth::user()->id;
        $reply->parent_id = $thread->id;
        $reply->type      = 'Reply';
        $reply->save();
        $reply->makeChildOf($thread);

        return redirect()->route('thread.show', [$thread->slug, $thread->name_slug])->with('success', 'Reply added.');
    }

    public function delete($id)
    {
        $reply = Post::findOrFail($id);

        $this->authorize('laraboard::reply-delete', $reply);

        $reply->status = 'Deleted';
        $reply->save();
        $reply->delete();

        if (!isset($reply->thread)) {
            return redirect()->route('forum.index')->with('success', 'Reply deleted.');
        }

        return redirect()->route('thread.show', [$reply->thread->slug, $reply->thread->name_slug])->with('success', 'Reply deleted.');
    }
}
