@can('thread-reply', $thread)
    <div id="quick-reply" class="row">
        <div class="col col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        Quick Reply
                    </div>
                </div>
                <div class="panel-body thread-row">
                    {!! Form::open(['route' => ['thread.reply', $thread->slug]]) !!}
                        <div class="form-group">
                            {!! Form::textarea('body', null, ['id' => 'reply-field', 'class' => 'form-control', 'rows' => '8', 'placeholder' => 'Reply to this thread...', 'data-provide' => 'markdown']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::button('Reply', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown/2.10.0/css/bootstrap-markdown.min.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown/2.10.0/js/bootstrap-markdown.min.js"></script>
        <script>
        @stack('quotes')
        </script>
    @endpush
@endcan