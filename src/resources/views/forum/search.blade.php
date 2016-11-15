@extends('laraboard::layouts.forum')

@section('title', 'Search Results: ' . $term)

@section('content')
<div class="row">
    <div class="col col-xs-12">
        <h1>Search Results: {{ $term }}</h1>
    </div>
</div>
<div class="row">
    <div class="col col-xs-12">
        @if ($posts->count() > 0)
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="col col-xs-6">Thread</th>
                        <th class="col col-xs-2">Replies</th>
                        <th class="col col-xs-4 hidden-xs">Latest Info</th>
                    </tr>
                </thead>
                <tbody>
                    @each('laraboard::thread.thread', $posts->pluck('thread'), 'thread')
                </tbody>
            </table>
        @else
        <p>No results found.</p>
        @endif
    </div>
</div>

<div class="row">
    <div class="col col-xs-7">
        {{ $posts->appends(['query' => null])->links() }}
    </div>
    <div class="col col-xs-5">
    </div>
</div>

@stop