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
        $thread = Thread::whereSlug($slug)->first();

        if (!$thread) {
            return redirect()->back()->with('error', 'Thread does not exist.');
        }

        if (!$thread->is_open) {
            return redirect()->back()->with('error', 'Thread is closed.  Replies can not be made.');
        }

        $this->authorize('laraboard::thread-reply', $thread);

        $this->validate($request, [
            'body' => 'required|max:4000',
        ]);

        $reply            = new Post;
        $reply->body      = $request->body;
        $reply->user_id   = \Auth::user()->id;
        $reply->type      = 'Reply';
        $reply->parent_id = $thread->id;
        $reply->save();
        $reply->makeChildOf($thread);

        return redirect()->route('thread.show', [$thread->board->category->slug, $thread->board->slug, $thread->slug, $thread->name_slug, 'page' => $thread->lastPage])->with('success', 'Reply added.');
    }
}
