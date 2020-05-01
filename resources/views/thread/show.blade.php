@extends('laraboard::layouts.forum')

@section('title', $thread->name . ' / ' . $thread->board->name . ' / ' . $thread->board->category->name)

@push('laraboard::actions')
    @can('laraboard::thread-reply', $thread)<a href="{{ route('thread.reply', $thread->slug) }}" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i><span> Post Reply</span></a>@endcan
    @can('laraboard::thread-subscribe', $thread)<a href="{{ route('thread.subscribe', $thread->slug) }}" class="btn btn-danger btn-sm"><i class="fa fa-bell-o"></i><span> Subscribe</span></a>@endcan
    @can('laraboard::thread-unsubscribe', $thread)<a href="{{ route('thread.unsubscribe', $thread->slug) }}" class="btn btn-danger btn-sm"><i class="fa fa-bell-slash-o"></i><span> Unsubscribe</span></a>@endcan
@endpush

@section('content')
<div class="row">
    <div class="col">
        <div class="float-right">

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
    <div class="col">
        <h3 class="mb-3">
            @if (!$thread->is_open)<span class="label label-warning"><i class="fa fa-ban" aria-hidden="true"></i> Thread Closed</span>@endif
            <a href="{{ route('thread.show', $thread->route) }}">{{ $thread->name }}</a>
        </h3>
    </div>
	<div class="col-sm-4">
        <div class="float-right">{{ $posts->links() }}</div>
	</div>
</div>

@foreach ($posts as $i => $post)
    @include('laraboard::post.post', ['page' => $posts->currentPage(), 'postNumber' => ($posts->currentPage() * $posts->perPage()) - $posts->perPage() + $i + 1])
@endforeach

@push('scripts')
<script>
$(function () {
  $('[data-toggle="popover"]').popover();
})
</script>
@endpush

<div class="row my-3">
    <div class="col col-xs-7">
        {{ $posts->links() }}
    </div>
</div>

@include('laraboard::post.reply')
@endsection
