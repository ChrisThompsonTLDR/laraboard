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
            \Event::fire(new \Christhompsontldr\Laraboard\Events\ThreadViewed($thread, \Auth::user()));
        }

        $posts = Post::where('id', $thread->id)->first()->descendantsAndSelf()->paginate(config('laraboard.thread.limit', 15));

        return view('laraboard::thread.show', compact('thread','posts'));
    }

    public function subscribe($slug)
    {
        $thread = Thread::whereSlug($slug)->firstOrFail();

        $this->authorize('laraboard::thread-subscribe', $thread);

        $sub = Subscription::updateOrCreate([
            'user_id' => \Auth::user()->id,
            'post_id' => $thread->id
        ]);

        return redirect()->back()->with('success', 'Thread subscription created.');
    }

    public function unsubscribe($slug)
    {
        $thread = Thread::whereSlug($slug)->firstOrFail();

        $this->authorize('laraboard::thread-unsubscribe', $thread);

        $sub = Subscription::where('post_id', $thread->id)->where('user_id', \Auth::user()->id)->delete();

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

        $thread = new Post;
        $thread->name    = $request->name;
        $thread->body    = $request->body;
        $thread->type    = 'Thread';
        $thread->user_id = \Auth::user()->id;
        $thread->save();
        $thread->makeChildOf($board);

        return redirect()->route('thread.show', [$thread->slug, $thread->name_slug]);
    }


    public function reply($slug)
    {
        $thread = Thread::whereSlug($slug)->firstOrFail();

        $this->authorize('thread-reply', $thread);

        \Event::fire(new \Christhompsontldr\Laraboard\Events\ThreadViewed($thread, \Auth::user()));

        $posts = Post::where('id', $thread->id)->first()->getDescendantsAndSelf()->reverse()->slice(0, 100);

        return view('laraboard::thread.reply', compact('thread','posts'));
    }
}
