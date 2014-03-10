@extends('frontend/layouts/default')

{{-- Page title --}}
@section('title')
Forgot Password ::
@parent
@stop

@section('banner')
@stop

@section('styles')
	@parent
	<link href="{{ asset('assets/css/signin.css') }}" rel="stylesheet">
@stop

@section('navigation')
@stop

{{-- Page content --}}
@section('content')
<form method="post" action="" class="form-signin">
	<a href="{{ route('signin') }}"><img style="width: 100%;" src="{{ asset('vfdpro.png') }}" class="form-signin-heading"></a>
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	<h2 class="form-signin-heading">Forgot Password</h2>
	<input type="text" class="form-control" name="email" id="email" placeholder="Email address" autofocus="" value="{{ Input::old('email') }}">
	<label></label>
	<button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
</form>
@stop
