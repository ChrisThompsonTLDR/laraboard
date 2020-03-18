@extends('laraboard::layouts.forum')

@section('title', 'New Thread')

@section('content')
<div class="card-group">
    <div class="card">
        <div class="card-header">
            @yield('title')<br />
            <em>Board: {{ $board->name }}</em>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => ['thread.create', $board->slug]]) !!}
                {!! Form::hidden('parent_id', $board->id) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Thread Title') !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'tabindex' => 1]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('body', 'Thread') !!}
                    {!! Form::textarea('body', old('body'), ['class' => 'form-control', 'data-provide' => 'markdown', 'rows' => '8', 'tabindex' => 2]) !!}
                </div>
                <div class="form-group">
                    {!! Form::button('Create Thread', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown/2.10.0/css/bootstrap-markdown.min.css" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown/2.10.0/js/bootstrap-markdown.min.js"></script>
@endpush
