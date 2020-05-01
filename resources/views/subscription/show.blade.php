@extends('laraboard::layouts.forum')

@section('title', 'Subscriptions')

@section('content')
<div class="row">
    <div class="col col-xs-12">
        <h1>Thread Subscriptions</h1>
    </div>
</div>
<div class="row">
    <div class="col col-xs-12">
        @if (auth()->user()->forumSubscriptions->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th class="col col-xs-6">Thread</th>
                        <th class="col col-xs-2">Replies</th>
                        <th class="col col-xs-4 d-none d-sm-block">Latest Info</th>
                    </tr>
                </thead>
                <tbody>
                    @each('laraboard::thread.thread', auth()->user()->forumSubscriptions->pluck('thread'), 'thread')
                </tbody>
            </table>
        @else
        <p>You have no unread alerts.</p>
        @endif
    </div>
</div>

@endsection
