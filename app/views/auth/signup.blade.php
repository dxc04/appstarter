@section('content')

	<legend>Please Sign Up</legend>
	{{ Form::open(array('url' => 'signup')) }}
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		<div class="form-group">
		<label>First Name</label>
		{{ Form::text('first_name', Input::old('first_name'), array('placeholder' => 'First name', 'class' => 'form-control')) }}
		{{ $errors->first('first_name', '<span class="help-block">:message</span>') }}
		</div>

		<div class="form-group">
		<label>Last Name</label>
		{{ Form::text('last_name', Input::old('last_name'), array('placeholder' => 'Last name', 'class' => 'form-control')) }}
		{{ $errors->first('last_name', '<span class="help-block">:message</span>') }}
		</div>

		<div class="form-group">
		<label>Email</label>
		{{ Form::text('email', Input::old('email'), array('placeholder' => 'Your email address', 'class' => 'form-control')) }}
		{{ $errors->first('email', '<span class="help-block">:message</span>') }}
		</div>

		<div class="form-group">
		<label>Password</label>
		{{ Form::password('password', array('placeholder' => 'Choose a password', 'class' => 'form-control')) }}
		{{ $errors->first('password', '<span class="help-block">:message</span>') }}
		</div>
<!--
		<div class="form-group">
			<label>Subscription Type</label>
			<div class="radio"><label>
			{{ Form::radio('subscription', 'standard_monthly', TRUE) }}
			Standard Monthly (&pound;8.99)
			</label></div>
			<div class="radio"><label>
			{{ Form::radio('subscription', 'standard_annually', FALSE) }}
			Standard Annually (&pound;59.99)
			</label></div>
			<div class="radio"><label>
			{{ Form::radio('subscription', 'professional_monthly', FALSE) }}
			Professional Monthly (&pound;11.99)
			</label></div>
			<div class="radio"><label>
			{{ Form::radio('subscription', 'professional_monthly', FALSE) }}
			Professional Annually (&pound;89.99)
			</label></div>
			{{ $errors->first('subscription', '<span class="help-block">:message</span>') }}
		</div>
-->
		<div class="form-group">
			<div class="col-md-6">
				<button class="btn btn-md btn-primary btn-block" type="submit">Sign up</button>
			</div>
			<div class="col-md-6">
				<a href="{{ url('signin') }}" class="btn btn-md btn-default btn-block">Cancel</a>
			</div>  
		</div>
	{{ Form::close() }}

	<legend>&nbsp;</legend>
	@if($errors->any())
	<div class="alert alert-dismissable alert-danger">
		<button type="button" class="close" data-dismiss="alert">×</button>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</div>
	@endif

@stop
