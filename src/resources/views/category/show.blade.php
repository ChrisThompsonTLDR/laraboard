@extends('laraboard::layouts.forum')

@section('title', $category->name)

@section('content')
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ $category->name }}
            <div class="pull-right">
                @if(Gate::allows('laraboard::board-create', $category) || Gate::allows('laraboard::category-edit', $category) || Gate::allows('laraboard::category-manage'))
                <div class="dropdown">
                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="category-{{ $category->slug }}-manage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="category-{{ $category->slug }}-manage">
                        @can('laraboard::board-create', $category)<li>{!! link_to_route('board.create', 'Create Board', $category->slug) !!}</li>@endcan
                        @can('laraboard::category-edit', $category)<li>{!! link_to_route('category.edit', 'Edit Category', $category->slug) !!}</li>@endcan
                    </ul>
                </div>
                @endif
            </div>
        </div>
        <div class="panel-body">
            @if (!empty($category->body))<div class="hidden-xs"><p class="text-muted">{!! $category->body !!}</p></div>@endif
            @if ($category->boards->count() == 0)<p>No boards have been created yet.</p>@endif
        </div>
        @if ($category->boards->count() > 0)
        <table class="table table-hover">
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
                        <strong><a href="{{ route('board.show', [$category->slug, $board->slug]) }}" data-clickable="true">{{ $board->name }}</a></strong>

                        @can('laraboard::category-manage')
                        <span class="dropdown">
                            <button class="btn btn-link btn-xs dropdown-toggle" type="button" id="category-{{ $category->slug }}-manage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="board-{{ $board->slug }}-manage">
                                @if($board->id != $category->boards->first()->id)<li>{!! link_to_route('board.reposition', 'Move Up', [$board->slug, 'up']) !!}</li>@endif
                                @if($board->id != $category->boards->last()->id)<li>{!! link_to_route('board.reposition', 'Move Down', [$board->slug, 'down']) !!}</li>@endif
                            </ul>
                        </span>
                        @endcan

                        <div class="hidden-xs"><em>{!! $board->body !!}</em></div>
                    </td>
                    <td class="col col-sm-1 col-xs-3"><span class="label label-primary">{{ number_format($board->threads->count()) }}</span></td>
                    <td class="col col-sm-1 col-xs-3"><span class="label label-primary">{{ number_format($board->posts->count()) }}</span></td>
                    <td class="col col-xs-4 hidden-xs">
                        @if ($board->threads->count() > 0)
                            <div>{!! link_to_route('thread.show', $board->threads->last()->name, [$category->slug, $board->slug, $board->threads->last()->slug, $board->threads->last()->name_slug]) !!}</div>
                            <small class="text-muted"><i class="fa fa-clock-o"></i> {!! $board->threads->last()->created !!}</small><br />
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
        @endif
    </div>
</div>
@endsection