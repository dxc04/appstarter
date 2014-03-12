<p>Hello {{ $user->first_name }},</p>

<p>Welcome to {{ $product }}! Please click on the following link to confirm your {{ $product }} account:</p>

<p><a href="{{ $activationUrl }}">{{ $activationUrl }}</a></p>

<p>Best regards,</p>
<p>{{ $product }} Team</p>
