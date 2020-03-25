@extends('laraboard::layouts.forum')

@section('title', 'Forums')

@section('content')
@forelse ($categories as $category)
<div class="card mb-4 shadow-sm">
    <div class="card-header">
        <a href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
        <div class="float-right">
            @if(Gate::allows('laraboard::board-create', $category) || Gate::allows('laraboard::category-edit', $category) || Gate::allows('laraboard::category-manage'))
            <div class="dropdown">
                <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="category-{{ $category->slug }}-manage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="category-{{ $category->slug }}-manage">
                    @can('laraboard::board-create', $category)<li>{!! link_to_route('board.create', 'Create Board', $category->slug) !!}</li>@endcan
                    @can('laraboard::category-edit', $category)<li>{!! link_to_route('category.edit', 'Edit Category', $category->slug) !!}</li>@endcan
                    @if(Gate::allows('laraboard::category-manage') && $categories->count() > 1)
                        <li role="separator" class="divider"></li>
                        @if($category->id != $categories->first()->id)<li>{!! link_to_route('category.reposition', 'Move Up', [$category->slug, 'up']) !!}</li>@endif
                        @if($category->id != $categories->last()->id)<li>{!! link_to_route('category.reposition', 'Move Down', [$category->slug, 'down']) !!}</li>@endif
                    @endif
                </ul>
            </div>
            @endif
        </div>
    </div>
    @if (!empty($category->body) || $category->boards->count() == 0)<div class="card-body">
        @if (!empty($category->body))<div class="d-none d-sm-block text-muted">{!! $category->body !!}</div>@endif
        @if ($category->boards->count() == 0)You are not a member of any gaming groups.@endif
    </div>@endif
    @if ($category->boards->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Board</th>
                <th scope="col" style="width: 10%;">Threads</th>
                <th scope="col" style="width: 10%;">Replies</th>
                <th scope="col" style="width: 25%;" class="d-none d-sm-table-cell">Latest Thread</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($category->boards as $board)
            <tr>
                <td>
                    <strong><a href="{{ route('board.show', $board->route) }}" data-clickable="true">{{ $board->name }}</a></strong>
                    <div class="d-none d-sm-block"><em>{!! $board->body !!}</em></div>
                </td>
                <td><span class="label label-primary">{{ number_format($board->threads->count()) }}</span></td>
                <td><span class="label label-primary">{{ number_format($board->posts->count()) }}</span></td>
                <td class="d-none d-sm-table-cell">
                    @if ($board->threads->count() > 0)
                        <div>{!! link_to_route('thread.show', $board->threads->last()->name, [$category->slug, $board->slug, $board->threads->last()->slug, $board->threads->last()->name_slug]) !!}</div>
                        <small class="text-muted"><i class="fa fa-clock-o"></i> {!! $board->threads->last()->created !!}</small><br />
                        <small class="text-muted"><i class="fa fa-user"></i> <a href="{{ url(config('laraboard.user.route') . $board->threads->last()->user->slug) }}">{{ $board->threads->last()->user->laraboard_name }}</a></small>
                    @else
                    --
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
@empty
<p>No forums created yet!</p>
@endforelse
@can('laraboard::category-manage') {!! link_to_route('category.create', 'Create Category', [], ['class' => 'btn btn-xs btn-primary']) !!}@endcan
@endsection
