@section('content')
	<legend><h3 class="text-center">Please Log In, or {{ HTML::link('signup', 'Register') }}</h3></legend>
		{{ Form::open(array('url' => 'signin')) }}
			<div class="form-group">
			  <label for="email">Email</label>
			  <input type="text" name="email" class="form-control" id="email">
			</div>
			<div class="form-group">
			  <!-- <a class="pull-right" href="#">Forgot password?</a> -->
			  <label for="password">Password</label>
			  <input type="password" name="password" class="form-control" id="passsword">
			</div>
			<button type="submit" class="text-center col-xs-12 btn btn-md btn-primary btn-block"> Log In </button>
      	    	{{ Form::close() }}
	
	<legend>&nbsp;</legend>
	@if($errors->any())
	<div class="alert alert-dismissable alert-danger">
		<button type="button" class="close" data-dismiss="alert">×</button>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</div>
	@endif

@stop
