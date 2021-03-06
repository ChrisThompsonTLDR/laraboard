@extends('laraboard::layouts.forum')

@section('title', 'Edit Category')

@section('content')
<div class="card-group">
    <div class="card">
        <div class="card-header">@yield('title')</div>
        <div class="card-body">
            {!! Form::model($category, ['route' => ['category.update', $category->slug]]) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Category') !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('body', 'Description') !!}
                    {!! Form::text('body', old('body'), ['class' => 'form-control', 'placeholder' => 'Short description...']) !!}
                    <div class="help-block">No HTML is allowed.</div>
                </div>
                <div class="form-group">
                    {!! Form::button('Edit Category', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
