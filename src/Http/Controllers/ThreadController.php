<?php

namespace Christhompsontldr\Laraboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;

use Christhompsontldr\Laraboard\Models\Category;
use Christhompsontldr\Laraboard\Models\Thread;
use Christhompsontldr\Laraboard\Models\Post;
use Christhompsontldr\Laraboard\Models\Subscription;
use Christhompsontldr\Laraboard\Models\Board;
use Christhompsontldr\Laraboard\Events\ThreadViewed;

class ThreadController extends Controller
{

    public function index()
    {
        $topics = Topic::with('topic_id', 'user_id');

        return view('laraboard::topics.index', compact('topics'));
    }

    public function show(Category $category, Board $board, Thread $thread, $slug)
    {
        if (auth()->check()) {
            event(new ThreadViewed($thread, auth()->user()));
        }

        $posts = Post::where('id', $thread->id)->first()->descendantsAndSelf()
            ->with(['user'])
            ->withCount(['revisionHistory'])
            ->paginate(config('laraboard.post.limit', 15));

        //  something is wrong with the page being viewed
        if ($posts->count() == 0) {
            return redirect()->route('thread.show', $thread->lastRoute);
        }

        return view('laraboard::thread.show', compact('thread','posts'));
    }

    public function create(Board $board)
    {
        $this->authorize('laraboard::thread-create', $board);

        return view('laraboard::thread.create', compact('board'));
    }

    public function store(Board $board, Request $request)
    {
        $this->authorize('laraboard::thread-create', $board);

        $this->validate($request, [
            'name' => 'required|max:255',
            'body' => 'required|max:4000'
        ]);

        $thread            = new Thread;
        $thread->name      = $request->name;
        $thread->body      = $request->body;
        $thread->user_id   = auth()->id();
        $thread->parent_id = $board->id;

        if (!$thread->save()) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create thread.');
        }

        $thread->makeChildOf($board);

        return redirect()->route('thread.show', $thread->route);
    }

    public function reply(Thread $thread)
    {
        $this->authorize('laraboard::thread-reply', $thread);

        event(new ThreadViewed($thread, auth()->user()));

        $posts = Post::where('id', $thread->id)->first()->getDescendantsAndSelf()->reverse()->slice(0, 100);

        return view('laraboard::thread.reply', compact('thread','posts'));
    }

    public function close(Thread $thread)
    {
        if (!$thread) {
            return redirect()->back()->with('error', 'This thread is already closed.');
        }

        $thread->status = 'Closed';

        if (!$thread->save()) {
            return redirect()->back()->with('error', 'There was an issue closing the thread.');
        }

        return redirect()->back()->with('success', 'Thread closed.');
    }

    public function open(Thread $thread)
    {
        if (!$thread) {
            return redirect()->back()->with('error', 'This thread is already open.');
        }

        $thread->status = 'Open';

        if (!$thread->save()) {
            return redirect()->back()->with('error', 'There was an issue opening the thread.');
        }

        return redirect()->back()->with('success', 'Thread opened.');
    }
}
