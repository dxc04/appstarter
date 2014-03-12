@extends('emails/layouts/default')

@section('content')
<p>Hello {{ $user->first_name }},</p>

<p>Welcome to BizPlanner! Please click on the following link to confirm your Virtual FD Pro account:</p>

<p><a href="{{ $activationUrl }}">{{ $activationUrl }}</a></p>

<p>Once confirmed, you can now login using the following credentials:</p>
<ol>
	<li>Username: {{ $user->email }}</li>
	<li>Password: {{ $password }}</li>
</ol>

<p>Best regards,<br>
{{ $accountant->full_name }}</p>
@stop
