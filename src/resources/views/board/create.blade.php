@extends('laraboard::layouts.forum')

@section('title', 'Create Board')

@section('content')
<div class="panel-group">
    <div class="panel panel-primary">
        <div class="panel-heading">@yield('title')</div>
        <div class="panel-body">
            {!! Form::open(['route' => 'board.store']) !!}
                <div class="form-group">
                    {!! Form::label('parent_id', 'Category') !!}
                    {!! Form::select('parent_id', $categories, old('parent_id', $parent_id), ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('name', 'Board Name') !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('body', 'Description') !!}
                    {!! Form::text('body', old('body'), ['class' => 'form-control', 'placeholder' => 'Short description...']) !!}
                </div>
                <div class="form-group">
                    {!! Form::button('Create Board', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop