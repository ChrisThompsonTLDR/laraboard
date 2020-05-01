<div id="p-{{ $post->slug }}" class="card shadow-sm mb-2">
    <div class="card-header text-muted small pb-1 pt-2">
        <a href="{{ route('thread.show', array_merge($thread->route, ['page' => $page])) }}#p-{{ $postNumber }}" id="p-{{ $postNumber }}" title="permalink">#{{ number_format($postNumber) }}</a>
        <small class="float-right">{{ $post->created }}</small>
    </div>
    <div class="card-body thread-row">
        <div class="row">
            <div class="col-sm-2">
                @if (config('laraboard.user.avatar'))
                <img class="img-fluid avatar" src="{!! $post->user->{config('laraboard.user.avatar')} !!}">
                @endif

                <a href="{{ url(config('laraboard.user.route') . $post->user->slug) }}">{{ $post->user->laraboard_name }}</a>

                <div class="d-block d-sm-none">
                    <div class="text-muted float-right"><small>Posts: {{ $post->user->post_count }}</small></div>
                </div>

                <hr class="d-block d-sm-none">

                <div class="d-none d-sm-block">
                    <div class="text-muted text-left"><small>Posts: {{ $post->user->post_count }}</small></div>
                    <div class="text-muted text-left"><small>Joined: {{ $post->user->created }}</small></div>
                </div>

                @if ($messaging)
                <div class="forum-icons">
                    <a href="{{ route('messages.create', $post->user->laraboard_name) }}"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                </div>
                @endif
            </div>
            <div class="col-sm-10">
                @if ($post->status != 'Deleted')
                <div class="laraboard-post-body">
                    {!! $post->body_html !!}
                </div>
                @else
                <em class="text-muted">Deleted: {!! $post->deleted !!}</em>
                @endif

                <?php $delete_modal = htmlentities('<p>Are you sure you want to delete this post?</p>' . link_to_route('post.delete', 'Delete', $post->id, ['class' => 'btn btn-danger btn-sm'])); ?>
                @if($post->status != 'Deleted') @can('laraboard::post-delete', $post)<a tabindex="0" class="btn btn-primary btn-sm" role="button" data-placement="right" data-toggle="popover" data-trigger="focus" title="Delete Post?" data-html="true" data-content="<?php echo $delete_modal; ?>"><i class="fa fa-ban"></i> Delete</a>@endcan @endif
                @can('laraboard::reply-edit', $post)<a href="{{ route('post.edit', $post->id ) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Edit</a>@endcan
                @can('laraboard::thread-reply', $post)
                    <button id="btn-quote-{{ $post->slug }}" class="btn btn-primary btn-sm"><i class="fa fa-quote-left"></i><span> Quote</span></button>
                    <div id="quote-{{ $post->slug }}" class="d-none">{{ '> ' . str_replace("\n", "\n> ", $post->body) }}</div>
                    @push('quotes')
                    $('#btn-quote-{{ $post->slug }}').click(function(e) {
                        $('#reply-field').data('markdown').replaceSelection($('#quote-{{ $post->slug }}').text());
                    });
                    @endpush
                @endcan

                @if ($post->revision_history_count > 0)
                <div id="laraboard-updated-by" class="text-muted">
                    <hr>
                    <small>Edited: @if ($post->updatedByUser){{ $post->updatedByUser->laraboard_name }} at @endif{{ $post->updated }}</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
