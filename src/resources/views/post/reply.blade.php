{!! Form::open(['route' => ['thread.reply', $thread->slug]]) !!}
    <div class="form-group">
        {!! Form::label('body', 'Reply') !!}
        {!! Form::textarea('body', old('body'), ['class' => 'form-control summernote', 'rows' => '8', 'placeholder' => 'Reply to this thread...']) !!}
    </div>
    <div class="form-group">
        {!! Form::button('Reply', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
    </div>
{!! Form::close() !!}

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.min.js"></script>
@endpush