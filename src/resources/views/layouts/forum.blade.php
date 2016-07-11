@push('styles')
<link href="{!! asset('css/laraboard.css') !!}" rel="stylesheet">
@endpush

@section('content')
<div id="forums">
    @yield('content')
</div>
@overwrite

@extends('layouts.app')