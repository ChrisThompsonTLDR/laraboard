@extends('laraboard::layouts.forum')

@section('title'){{ $board->name }}@endsection

@push('laraboard::actions')
<div class="float-right">
    @can('laraboard::thread-create', $board)<a href="{{ route('thread.create', $board) }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i><span> Create Thread</span></a>@endcan

    @if(Gate::allows('laraboard::board-edit', $board))
    <div class="dropdown">
        <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="board-{{ $board->slug }}-manage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="board-{{ $board->slug }}-manage">
            @can('laraboard::board-edit', $board)<li><a href="{{ route('board.edit', $board->slug) }}"> Board Edit</a></li>@endcan
        </ul>
    </div>
    @endif
</div>
@endpush

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            {{ $board->name }}
        </div>
        <div class="card-body">
            @if(!empty($board->body))<span class="text-muted">{!! $board->body !!}</span>@endif

            @if ($threads->count() == 0)
            This board has no threads.
            @endif
        </div>
        @if ($threads->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Thread</th>
                    <th scope="col" class="w-25">Replies</th>
                    <th scope="col" class="w-25">Latest Info</th>
                </tr>
            </thead>
            <tbody>
                @each('laraboard::thread.thread', $threads, 'thread')
            </tbody>
        </table>
        @endif
    </div>
    @if ($threads->count() > 0)
        {{ $threads->links() }}
    @endif
@endsection
