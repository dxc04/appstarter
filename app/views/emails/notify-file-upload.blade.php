@extends('emails/layouts/default')

@section('content')
<p>Hello {{ $accountant->user->first_name }},</p>

<p>Your client, {{ $client->user->getFullName() }}, has uploaded a new {{ $client_file->formatted_type}}:<br>
<ul>
	<li> File: {{ $client_file->name }}</li>
</ul>

<p>Best regards,<br>
Virtual FD Pro Team</p>
@stop
