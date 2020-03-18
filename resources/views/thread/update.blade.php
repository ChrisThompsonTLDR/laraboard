@extends('laraboard::layouts.forum')

@section('page-title', 'Edit Topic')

@section('content')
@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	</div>
@endif
	<form action="{{ url('/topic/' . $topic->id . '/update') }}" method="post">
		{{ csrf_field() }}
		<div class="form-group">
			<label for="title" class="control-label">Title</label>
			<input type="text" name="title" id="topic-title" class="form-control" value="{{ $topic->title }}" placeholder="Title...">
		</div>
		<div class="form-group">
			<label for="body" class="control-label">Body</label>
			<textarea name="body" rows="8" class="form-control" placeholder="Body...">{{ $topic->body }}</textarea>
		</div>
		<button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Topic</button>
	</form>
@endsection
