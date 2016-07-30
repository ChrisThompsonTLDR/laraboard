<div id="p-{{ $post->slug }}" class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title clearfix">
            <?php $delete_modal = htmlentities('<p>Are you sure you want to delete this post?</p>' . link_to_route('reply.delete', 'Delete', $post->id, ['class' => 'btn btn-danger btn-sm'])); ?>
            @if($post->status != 'Deleted') @can('laraboard::reply-delete', $post)<a tabindex="0" class="btn btn-primary btn-xs" role="button" data-placement="left" data-toggle="popover" data-trigger="focus" title="Delete Post?" data-html="true" data-content="<?php echo $delete_modal; ?>"><i class="fa fa-ban"></i> Delete</a>@endcan @endif
            @can('laraboard::reply-edit', $post)<a href="{{ url('/reply/' . $post->id . '/edit') }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit</a>@endcan
            @can('laraboard::reply-create', $post)<a href="{{ url('/reply/' . $post->id . '/quote') }}" class="btn btn-primary btn-xs"><i class="fa fa-quote-left"></i> Quote</a>@endcan
            <small class="pull-right text-muted">{{ $post->created }}</small>
        </div>
    </div>
    <div class="panel-body thread-row">
        <div class="row">
            <div class="col-xs-2">
                <a href="{{ url(config('laraboard.user.route') . $post->user->slug) }}">{{ $post->user->display_name }}</a>
                <div class="text-muted text-left"><small>Posts: {{ $post->user->post_count }}</small></div>
                <div class="text-muted text-left"><small>Joined: {{ $post->user->created }}</small></div>
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
                {!! $post->body_html !!}
                @else
                <em class="text-muted">Deleted: {!! $post->deleted !!}</em>
                @endif
            </div>
        </div>
    </div>
</div>