@if (\Auth::check())
<span class="dropdown">
    <button class="btn btn-link dropdown-toggle" type="button" id="laraboard-alerts-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <i class="fa fa-flag{{ \Auth::user()->unreadAlerts->count() > 0 ? '' : '-o' }}" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="laraboard-alerts-btn">
        @forelse (\Auth::user()->alerts as $alert)
            <li>
                <a href="{{ route('thread.show', [$alert->board->category->slug, $alert->board->slug, $alert->slug, $alert->name_slug]) }}">
                    {{ $alert->name }}<br />
                    <small class="text-muted">{{ \Auth::user()->notifications->where('data.alert.parent_id', $alert->id)->first()->created_at->format('F j, Y g:ia T') }}</small>
                </a>

            </li>
        @empty
            <li>You have no alerts.</li>
        @endforelse
    </ul>
</span>
@endif