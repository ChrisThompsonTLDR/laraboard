@if(isset($crumbs) && count($crumbs) > 0)
<ol class="breadcrumb">
    <li><a href="{{ route('forum.index') }}">Forum</a></li>
    @foreach($crumbs as $crumb)
    <li>@if(!empty($crumb['url']))<a href="{{ $crumb['url'] }}">@endif{{ $crumb['name'] }}@if(!empty($crumb['url']))</a>@endif</li>
    @endforeach
</ol>
@endif