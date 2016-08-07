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
        @if (Auth::user()->forumSubscriptionAlerts->count() > 0)
            @foreach(Auth::user()->subscriptionAlerts as $alert)
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
                @foreach (Auth::user()->subscriptionAlerts as $alert)<?php $thread = $alert->thread; ?>
                    <tr>
                        <td class="col col-xs-6">
                            <div>
                                <a href="{{ route('thread.show', [$thread->slug, $thread->name_slug]) }}" data-clickable="true">{{ $thread->name }}</a>
                            </div>
                            <small class="text-muted">Author: <a href="{{ url(config('laraboard.user.route') . $thread->user->slug) }}">{{ $thread->user->display_name }}</a></small><br />
                            <small class="text-muted">Posted: {!! $thread->created !!}</small>
                        </td>
                        <td class="col col-xs-2"><span class="label label-primary">{{ number_format($thread->replies->count()) }}</span></td>
                        <?php /*<td class="col-md-1 text-right"><span class="badge">xx</span></td>*/ ?>
                        <td class="col col-xs-4 hidden-xs">
                            @if ($thread->replies->count() > 0)
                                <small class="text-muted"><i class="fa fa-clock-o"></i> {!! $thread->replies->last()->created !!}</small><br />
                                <small class="text-muted"><i class="fa fa-user"></i> <a href="{{ url(config('laraboard.user.route') . $thread->user->slug) }}">{{ $thread->user->display_name }}</a></small>
                            @else
                            --
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endforeach
        @else
        <p>You have no unread alerts.</p>
        @endif
    </div>
</div>

@stop