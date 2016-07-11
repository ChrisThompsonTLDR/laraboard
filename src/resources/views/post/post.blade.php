<div id="p{{ $post->slug }}" class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">@date($post->created_at) @time($post->created_at)
            <?php /*<div class="pull-right">
                <div class="btn-group hidden-md hidden-lg">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                        Actions
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        @can('thread-subscribe', $post->thread)<li><a href="{{ route('thread.subscribe', $post->thread->slug) }}"><i class="fa fa-bell-o"></i> Subscribe</a></li>@endcan
                        <li><a href="{{ url('/topic/' . $post->id . '/report') }}"><i class="fa fa-flag"></i> Report</a></li>
                        <li><a href="{{ url('/topic/' . $post->id . '/ignore') }}"><i class="fa fa-minus-circle"></i> Ignore</a></li>
                        <li><a href="{{ url('/topic/' . $post->id . '#r' . $post->id) }}"><i class="fa fa-link"></i> Permalink</a></li>
                    </ul>
                </div>
                @can('thread-subscribe', $post->thread)<a class="btn btn-default btn-xs hidden-sm hidden-xs" href="{{ route('thread.subscribe', $post->thread->slug) }}"><i class="fa fa-bell-o"></i> Subscribe</a>@endcan
                <a class="btn btn-default btn-xs hidden-sm hidden-xs" href="{{ url('/topic/' . $post->id . '/report') }}"><i class="fa fa-flag"></i> Report</a>
                <a class="btn btn-default btn-xs hidden-sm hidden-xs" href="{{ url('/topic/' . $post->id . '/ignore') }}"><i class="fa fa-minus-circle"></i> Ignore</a>
                <a class="btn btn-default btn-xs hidden-sm hidden-xs" href="{{ url('/topic/' . $post->id . '#r' . $post->id) }}"><i class="fa fa-link"></i> Permalink</a>
            </div>*/ ?>
        </div>
    </div>
    <div class="panel-body thread-row">
        <div class="row">
            <div class="col-xs-2">
                <a href="{{ url(config('laraboard.user.route') . $post->user->slug) }}">{{ $post->user->display_name }}</a>
                <div class="text-muted text-left"><small>Posts: {{ $post->user->post_count }}</small></div>
                <div class="text-muted text-left"><small>Joined: @date($post->user->created_at)</small></div>
                @if ($post->user->avatar)
                <img class="img-thumbnail img-responsive avatar" src="{!! asset('uploads/users/110x110/' . $post->user->username . '.png') !!}" alt="" />
                @endif
                @if ($messaging)
                <div class="forum-icons">
                    <a href="{{ route('messages.create', $post->user->display_name) }}"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                </div>
                @endif
            </div>
            <div class="col col-xs-10">
                @if ($post->status != 'Deleted')
                {!! $post->body !!}
                @else
                <em class="text-muted">Deleted: @date($post->updated_at) @time($post->updated_at)</em>
                @endif
            </div>
        </div>
    </div>
    <?php $delete_modal = htmlentities('<p>Are you sure you want to delete this post?</p>' . link_to_route('reply.delete', 'Delete', $post->id, ['class' => 'btn btn-danger btn-sm'])); ?>
    <div class="panel-footer clearfix">
        <div class="pull-right">
            @if($post->status != 'Deleted') @can('reply-delete', $post)<a tabindex="0" class="btn btn-warning btn-xs" role="button" data-placement="left" data-toggle="popover" data-trigger="focus" title="Delete Post?" data-html="true" data-content="<?php echo $delete_modal; ?>"><i class="fa fa-ban"></i> Delete</a>@endcan @endif
            @can('reply-edit', $post)<a href="{{ url('/reply/' . $post->id . '/edit') }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit</a>@endcan
            @can('reply-create', $post)<a href="{{ url('/reply/' . $post->id . '/quote') }}" class="btn btn-primary btn-xs"><i class="fa fa-quote-left"></i> Quote</a>@endcan
        </div>
    </div>
</div>