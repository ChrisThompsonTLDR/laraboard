@extends('laraboard::layouts.forum')

@section('title'){{ $board->name }}@endsection

@section('content')
<div class="row">
    <div class="col col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                {{ $board->name }}

                <div class="pull-right">
                    @can('laraboard::thread-create', $board)<a href="{{ route('thread.create', $board->slug) }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i><span> Create Thread</span></a>@endcan

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
            </div>
            <div class="panel-body">
                @if(!empty($board->body))<p class="text-muted">{!! $board->body !!}</p>@endif
                @if ($threads->count() > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col col-xs-6">Thread</th>
                            <th class="col col-xs-2">Replies</th>
                            <?php /*<th class="text-right">Views</th>*/ ?>
                            <th class="col col-xs-4 hidden-xs">Latest Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @each('laraboard::thread.thread', $threads, 'thread')
                    </tbody>
                </table>
                @else
                <p>This board has no threads.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@if ($threads->count() > 0)
<div class="row">
    <div class="col col-xs-12">
    {{ $threads->links() }}
    </div>
</div>
@endif
@stop