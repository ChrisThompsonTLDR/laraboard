@extends('laraboard::layouts.forum')

@section('title', 'Forums')

@section('content')
@forelse ($categories as $category)
<div class="panel-group">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <i class="fa fa-folder-open-o fa-fw"></i> {{ $category->name }}
            <div class="pull-right">
                @can('board-create', $category) {!! link_to_route('board.create', 'Create a Board', $category->id, ['class' => 'btn btn-xs btn-warning']) !!}@endcan
                @can('category-edit', $category) {!! link_to_route('forum.edit', 'Edit Category', $category->id, ['class' => 'btn btn-xs btn-warning']) !!}@endcan
            </div>
        </div>
        @if (!empty($category->body))
        <div class="panel-body">
            <div class="hidden-xs"><em>{!! $category->body !!}</em></div>
        </div>
        @endif
        @if ($category->boards->count() > 0)
        <table class="table table-hover table-clickable">
            <thead>
                <tr>
                    <th class="col col-xs-6">Board Name</th>
                    <th class="col col-sm-1 col-xs-3">Threads</th>
                    <th class="col col-sm-1 col-xs-3">Replies</th>
                    <th class="col col-xs-4 hidden-xs">Latest Thread</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($category->boards as $board)
                <?php /*@can('board-show', $board)*/ ?>
                <tr>
                    <td class="col col-xs-6">
                        <strong><a href="{{ route('board.show', $board->slug) }}" data-clickable="true">{{ $board->name }}</a></strong>
                        <div class="hidden-xs"><em>{!! $board->body !!}</em></div>
                    </td>
                    <td class="col col-sm-1 col-xs-3"><span class="label label-primary">{{ number_format($board->threads->count()) }}</span></td>
                    <td class="col col-sm-1 col-xs-3"><span class="label label-primary">{{ number_format($board->posts->count()) }}</span></td>
                    <td class="col col-xs-4 hidden-xs">
                        @if ($board->threads->count() > 0)
                            <div>{!! link_to_route('thread.show', $board->threads->last()->name, [$board->threads->last()->slug]) !!}</div>
                            <small class="text-muted"><i class="fa fa-clock-o"></i> @date($board->threads->last()->created_at) @time($board->threads->last()->created_at)</small><br />
                            <small class="text-muted"><i class="fa fa-user"></i> <a href="{{ url(config('laraboard.user.route') . $board->threads->last()->user->slug) }}">{{ $board->threads->last()->user->display_name }}</a></small>
                        @else
                        --
                        @endif
                    </td>
                </tr>
                <?php /*@endcan*/ ?>
            @endforeach
            </tbody>
        </table>
        @else
        <div class="container"><div class="row"><div class="col col-xs-12"><p>No boards have been created yet.</p></div></div></div>
        @endif
    </div>
</div>
@empty
<p>No forums created yet!</p>
@endforelse
@can('category-create', $category) {!! link_to_route('forum.create', 'Create Forum', [], ['class' => 'btn btn-xs btn-warning']) !!}@endcan
@stop