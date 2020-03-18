<tr>
    <td>
        <div>
            <a href="{{ route('thread.show', $thread->route) }}" data-clickable="true">{{ $thread->name }}</a>
        </div>
        <small class="text-muted">Author: <a href="{{ url(config('laraboard.user.route') . $thread->user->slug) }}">{{ $thread->user->laraboard_name }}</a></small><br />
        <small class="text-muted">Posted: {!! $thread->created !!}</small>
    </td>
    <td><span class="label label-primary">{{ number_format($thread->replies->count()) }}</span></td>
    <td>
        @if ($thread->replies->count() > 0)
            <small class="text-muted"><i class="fa fa-clock-o"></i> {!! $thread->replies->last()->created !!}</small><br />
            <small class="text-muted"><i class="fa fa-user"></i> <a href="{{ url(config('laraboard.user.route') . $thread->user->slug) }}">{{ $thread->user->laraboard_name }}</a></small>
        @else
        --
        @endif
    </td>
</tr>
