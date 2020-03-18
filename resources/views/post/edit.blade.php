@extends('laraboard::layouts.forum')

@section('title', $post->thread->name . ' / ' . $post->thread->board->name . ' / ' . $post->thread->board->category->name)

@section('content')
@can('laraboard::post-edit', $post)
    <div class="row">
        <div class="col col-xs-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Edit Post
                    </div>
                </div>
                <div class="card-body thread-row">
                    {!! Form::model($post, ['route' => ['post.update', $post->id]]) !!}
                        <div class="form-group">
                            {!! Form::textarea('body', null, ['class' => 'form-control', 'rows' => '8', 'data-provide' => 'markdown']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::button('Edit', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
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
    @endpush
@endcan
@endsection
