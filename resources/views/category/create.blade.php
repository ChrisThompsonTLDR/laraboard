@extends('laraboard::layouts.forum')

@section('title', 'Create a Forum')

@section('content')
<div class="card-group">
    <div class="card">
        <div class="card-header">@yield('title')</div>
        <div class="card-body">
            {!! Form::open(['route' => 'category.store']) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Forum Name') !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('body', 'Description') !!}
                    {!! Form::text('body', old('body'), ['class' => 'form-control', 'placeholder' => 'Short description...']) !!}
                    <div class="help-block">No HTML is allowed.</div>
                </div>
                <div class="form-group">
                    {!! Form::button('Create Category', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
