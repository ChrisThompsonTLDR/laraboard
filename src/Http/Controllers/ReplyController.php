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
        $reply->type      = 'Reply';
        $reply->save();
        $reply->makeChildOf($thread);

        /**
        * @todo find the last page and redirect there
        */

        return redirect()->route('thread.show', [$thread->board->category->slug, $thread->board->slug, $thread->slug, $thread->name_slug])->with('success', 'Reply added.');
    }
}
