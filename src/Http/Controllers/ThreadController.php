<?php

namespace Christhompsontldr\Laraboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;

use Christhompsontldr\Laraboard\Models\Thread;
use Christhompsontldr\Laraboard\Models\Post;
use Christhompsontldr\Laraboard\Models\Subscription;
use Christhompsontldr\Laraboard\Models\Board;

class ThreadController extends Controller
{

    public function index()
    {
        $topics = Topic::with('topic_id', 'user_id');

        return view('laraboard::topics.index', compact('topics'));
    }

    public function show($category_slug, $board_slug, $slug, $name_slug = null)
    {
        $thread = Thread::whereSlug($slug)->firstOrFail();

        //  redirect with the correct slug
        if (is_null($name_slug) || $thread->name_slug != $name_slug) {
            return redirect()->route('thread.show', [$thread->slug, $thread->name_slug], 301);
        }

        if (\Auth::check()) {
            event(new \Christhompsontldr\Laraboard\Events\ThreadViewed($thread, \Auth::user()));
        }

        $posts = Post::where('id', $thread->id)->first()->descendantsAndSelf()->paginate(config('laraboard.post.limit', 15));

        //  something is wrong with the page being viewed
        if ($posts->count() == 0) {
            return redirect()->route('thread.show', $thread->lastRoute);
        }

        return view('laraboard::thread.show', compact('thread','posts'));
    }

    public function subscribe($slug)
    {
        $thread = Thread::whereSlug($slug)->firstOrFail();

        $this->authorize('laraboard::thread-subscribe', $thread);

        $sub = Subscription::updateOrCreate([
            'user_id' => \Auth::id(),
            'post_id' => $thread->id
        ],[
            'user_id' => \Auth::id(),
            'post_id' => $thread->id
        ]);

        return redirect()->back()->with('success', 'Thread subscription created.');
    }

    public function unsubscribe($slug)
    {
        $thread = Thread::whereSlug($slug)->firstOrFail();

        $this->authorize('laraboard::thread-unsubscribe', $thread);

        $sub = Subscription::where('post_id', $thread->id)->where('user_id', \Auth::id())->delete();

        return redirect()->back()->with('success', 'Thread subscription deleted.');
    }

    public function create($parent_slug)
    {
        $board = Board::whereSlug($parent_slug)->firstOrFail();

        if (Gate::denies('laraboard::thread-create', $board)) {
            abort(403);
        }

        return view('laraboard::thread.create', compact('board'));
    }

    public function store(Request $request)
    {
        $board = Board::findOrFail($request->parent_id);

        $this->authorize('laraboard::thread-create', $board);

        $this->validate($request, [
            'name' => 'required|max:255',
            'body' => 'required|max:4000'
        ]);

        $post          = new Post;
        $post->name    = $request->name;
        $post->body    = $request->body;
        $post->type    = 'Thread';
        $post->user_id = \Auth::user()->id;
        $post->save();
        $post->makeChildOf($board);

        $thread = Thread::findOrFail($post->id);

        return redirect()->route('thread.show', [$thread->board->category->slug, $thread->board->slug, $thread->slug, $thread->name_slug]);
    }


    public function reply($slug)
    {
        $thread = Thread::whereSlug($slug)->firstOrFail();

        $this->authorize('laraboard::thread-reply', $thread);

        event(new \Christhompsontldr\Laraboard\Events\ThreadViewed($thread, \Auth::user()));

        $posts = Post::where('id', $thread->id)->first()->getDescendantsAndSelf()->reverse()->slice(0, 100);

        return view('laraboard::thread.reply', compact('thread','posts'));
    }


    public function close($slug)
    {
        $thread = Thread::whereSlug($slug)->open()->first();

        if (!$thread) {
            return redirect()->back()->with('error', 'This thread is already closed.');
        }

        $thread->status = 'Closed';

        if (!$thread->save()) {
            return redirect()->back()->with('error', 'There was an issue closing the thread.');
        }

        return redirect()->back()->with('success', 'Thread closed.');
    }


    public function open($slug)
    {
        $thread = Thread::whereSlug($slug)->closed()->first();

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
