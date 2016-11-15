@extends('laraboard::layouts.forum')

@section('title', $thread->name . ' / ' . $thread->board->name . ' / ' . $thread->board->category->name)

@section('content')
<div class="row">
	<div class="col col-xs-7">
        {{ $posts->links() }}
	</div>
    <div class="col col-xs-5">
        <div class="pull-right">
            @can('laraboard::thread-reply', $thread)<a href="{{ route('thread.reply', $thread->slug) }}" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i><span> Post Reply</span></a>@endcan
            @can('laraboard::thread-subscribe', $thread)<a href="{{ route('thread.subscribe', $thread->slug) }}" class="btn btn-danger btn-sm"><i class="fa fa-bell-o"></i><span> Subscribe</span></a>@endcan
            @can('laraboard::thread-unsubscribe', $thread)<a href="{{ route('thread.unsubscribe', $thread->slug) }}" class="btn btn-danger btn-sm"><i class="fa fa-bell-slash-o"></i><span> Unsubscribe</span></a>@endcan

            @can('laraboard::thread-close', $thread)
            <span class="dropdown">
                <button class="btn btn-sm btn-default dropdown-toggle" type="button" id="thread-{{ $thread->slug }}-manage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="thread-{{ $thread->slug }}-manage">
                    @if ($thread->status == 'Open')
                    <li><a href="{{ route('thread.close', $thread->slug) }}"> Close Thread</a></li>
                    @else
                    <li><a href="{{ route('thread.open', $thread->slug) }}"> Open Thread</a></li>
                    @endif
                </ul>
            </span>
            @endif
        </div>
    </div>
</div>

<div id="thead-title" class="row">
    <div class="col col-xs-12">
        <h3>
            @if (!$thread->is_open)<span class="label label-warning"><i class="fa fa-ban" aria-hidden="true"></i> Thread Closed</span>@endif
            {!! link_to_route('thread.show', $thread->name, [$thread->board->category->slug, $thread->board->slug, $thread->slug, $thread->name_slug]) !!}
        </h3>
    </div>
</div>

@each('laraboard::post.post', $posts, 'post')

@push('scripts')
    <script>
    $(function () {
      $('[data-toggle="popover"]').popover();
    })
    </script>
@endpush

<div class="row">
    <div class="col col-xs-7">
        {{ $posts->links() }}
    </div>
    <div class="col col-xs-5">
        <div class="pull-right">
            @can('laraboard::thread-reply', $thread)<a href="{{ route('thread.reply', $thread->slug) }}" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i><span> Post Reply</span></a>@endcan
            @can('laraboard::thread-subscribe', $thread)<a href="{{ route('thread.subscribe', $thread->slug) }}" class="btn btn-danger btn-sm"><i class="fa fa-bell-o"></i><span> Subscribe</span></a>@endcan
            @can('laraboard::thread-unsubscribe', $thread)<a href="{{ route('thread.unsubscribe', $thread->slug) }}" class="btn btn-danger btn-sm"><i class="fa fa-bell-slash-o"></i><span> Unsubscribe</span></a>@endcan
        </div>
    </div>
</div>

@include('laraboard::post.reply')
@endsection