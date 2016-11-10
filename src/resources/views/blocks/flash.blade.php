@if (session('success'))
    <div class="alert alert-success">
        {!! session('success') !!}
    </div>
@endif
@if (session('danger'))
    <div class="alert alert-danger">
        {!! session('danger') !!}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {!! session('error') !!}
    </div>
@endif
@if (isset($errors) && count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif