@extends('laraboard::layouts.forum')

@section('title', $thread->name . ' / ' . $thread->board->name)

@section('content')
<div id="board-title" class="row">
    <div class="col col-xs-12">
        <h4>{!! link_to_route('board.show', $thread->board->name, $thread->board->slug) !!}</h4>
    </div>
</div>
<div id="thead-title" class="row">
	<div class="col col-xs-12">
		<h3>{!! link_to_route('thread.show', $thread->name, [$thread->slug, $thread->name_slug]) !!}</h3>
	</div>
    <div class="col col-xs-6">
        <div class="pull-right">
            @can('thread-view', $thread)<a href="{{ route('thread.show', [$thread->slug,$thread->name_slug]) }}" class="btn btn-primary btn-sm"> View Thread</a>@endcan
            @can('laraboard::thread-subscribe', $thread)<a href="{{ route('thread.subscribe', $thread->slug) }}" class="btn btn-danger btn-sm"><i class="fa fa-bell-o"></i> Subscribe</a>@endcan
            <?php /*<a href="{{ url('/board/' . $thread->board_id. '/create') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Create Topic</a>*/ ?>
        </div>
    </div>
</div>

@can('laraboard::thread-reply', $thread)
    <div class="row">
        <div class="col col-xs-12">
            @include('laraboard::post.reply')
        </div>
    </div>
@endcan

@each('laraboard::post.post', $posts, 'post')

@push('scripts')
    <script>
    $(function () {
      $('[data-toggle="popover"]').popover();
    })
    </script>
@endpush

<div class="row">
    <div class="col col-xs-6"></div>
    <div class="col col-xs-6">
        <div class="pull-right">
            @can('thread-view', $thread)<a href="{{ route('thread.show', [$thread->slug,$thread->name_slug]) }}" class="btn btn-primary btn-sm"> View Thread</a>@endcan
            @can('laraboard::thread-subscribe', $thread)<a href="{{ route('thread.subscribe', $thread->slug) }}" class="btn btn-danger btn-sm"><i class="fa fa-bell-o"></i> Subscribe</a>@endcan
        </div>
    </div>
</div>
@stop