@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
@endpush

@section('content')
<div id="forums">
    @if(is_string($flash_blade = config('laraboard.view.flash')))@include($flash_blade)@endif
    @include('laraboard::blocks.breadcrumbs')
    @yield('content')
</div>
@overwrite

@extends(config('laraboard.view.layout', 'laraboard::layouts.app'))