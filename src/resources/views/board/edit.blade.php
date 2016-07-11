@extends('laraboard::layouts.forum')

@section('title', 'Edit Board')

@section('content')
<div class="panel-group">
    <div class="panel panel-primary">
        <div class="panel-heading">@yield('title')</div>
        <div class="panel-body">
            {!! Form::model($board, ['route' => 'board.update']) !!}
                {!! Form::hidden('id', $board->id) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Board Name') !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('body', 'Description') !!}
                    {!! Form::text('body', old('body'), ['class' => 'form-control', 'placeholder' => 'Short description...']) !!}
                </div>
                <div class="form-group">
                    {!! Form::button('Update Board', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop