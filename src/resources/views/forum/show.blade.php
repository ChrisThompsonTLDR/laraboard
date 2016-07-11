@extends('laraboard::layouts.forum')

@section('title', $board->name)

@section('content')
<div class="row">
    <div class="col col-xs-12">
        <h4>{!! link_to_route('forum.index', 'Forum') !!}</h4>
    </div>
</div>
<div id="board-title" class="row">
	<div class="col col-xs-9">
		<h3>{{ $board->name }}</h3>
	</div>
    <div class="col col-xs-3">
        <div class="pull-right">
            @can('thread-create', $board)<a href="{{ route('thread.create', $board->slug) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Create Thread</a>@endcan
            @can('board-edit', $board)<a href="{{ route('board.edit', $board->id) }}" class="btn btn-primary btn-sm"> Board Edit</a>@endcan
        </div>
    </div>
</div>
<div class="row">
	<div class="col col-xs-12">
		<div class="panel panel-primary">
			<div class="panel-heading">{!! $board->body !!}</div>
            <div class="panel-body">
                @if ($threads->count() > 0)
			    <table class="table table-hover table-clickable">
				    <thead>
					    <tr>
						    <th class="col col-xs-6">Thread</th>
						    <th class="col col-xs-2">Replies</th>
						    <?php /*<th class="text-right">Views</th>*/ ?>
						    <th class="col col-xs-4 hidden-xs">Latest Info</th>
					    </tr>
				    </thead>
				    <tbody>
				    @foreach ($threads as $thread)
					    <tr>
						    <td class="col col-xs-6">
							    <div>
								    <a href="{{ route('thread.show', [$thread->slug, $thread->name_slug]) }}" data-clickable="true">{{ $thread->name }}</a>
							    </div>
                                <small class="text-muted">Author: <a href="{{ url(config('laraboard.user.route') . $thread->user->slug) }}">{{ $thread->user->display_name }}</a></small><br />
                                <small class="text-muted">Posted: @date($thread->created_at) @time($thread->created_at)</small>
						    </td>
						    <td class="col col-xs-2"><span class="label label-primary">{{ number_format($thread->replies->count()) }}</span></td>
						    <?php /*<td class="col-md-1 text-right"><span class="badge">xx</span></td>*/ ?>
						    <td class="col col-xs-4 hidden-xs">
                                @if ($thread->replies->count() > 0)
                                    <small class="text-muted"><i class="fa fa-clock-o"></i> @date($thread->replies->last()->created_at) @time($thread->replies->last()->created_at)</small><br />
								    <small class="text-muted"><i class="fa fa-user"></i> <a href="{{ url(config('laraboard.user.route') . $thread->user->slug) }}">{{ $thread->user->display_name }}</a></small>
                                @else
                                --
                                @endif
						    </td>
					    </tr>
				    @endforeach
				    </tbody>
			    </table>
                @else
                <p>This board has no threads.</p>
                @endif
            </div>
		</div>
	</div>
</div>
<div class="row">
    <div class="col col-xs-12">
    {{ $threads->links() }}
    </div>
</div>
@stop