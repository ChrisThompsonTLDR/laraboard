@extends('laraboard::layouts.forum')

@section('title', 'New Thread')

@section('content')
<div class="panel-group">
    <div class="panel panel-primary">
        <div class="panel-heading">
            @yield('title')<br />
            <em>Board: {{ $board->name }}</em>
        </div>
        <div class="panel-body">
            {!! Form::open(['route' => ['thread.create', $board->slug]]) !!}
                {!! Form::hidden('parent_id', $board->id) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Thread Title') !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('body', 'Thread') !!}
                    {!! Form::textarea('body', old('body'), ['class' => 'form-control summernote', 'rows' => '8', 'placeholder' => 'Short description...']) !!}
                </div>
                <div class="form-group">
                    {!! Form::button('Create Thread', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.min.js"></script>
@endpush