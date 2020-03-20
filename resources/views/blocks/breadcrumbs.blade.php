<div class="row">
    @if(isset($crumbs) && count($crumbs) > 0)
    <div class="col-sm-10">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('forum.index') }}">Forum</a></li>
                @foreach($crumbs as $crumb)
                <li class="breadcrumb-item"@if(empty($crumb['url'])) aria-current="page"@endif>@if(!empty($crumb['url']))<a href="{{ $crumb['url'] }}">@endif{{ $crumb['name'] }}@if(!empty($crumb['url']))</a>@endif</li>
                @endforeach
            </ol>
        </nav>
    </div>
    @endif
    <div class="col-sm-2">
        @stack('laraboard::actions')
    </div>
</div>
