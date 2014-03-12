<?php

use \Omnipay\Common\GatewayFactory;

class AuthController extends BaseController {

	protected $layout = 'layout.auth';

	protected $subscription_currency = 'GBP';

	protected $subscription_types = array(
	);

	/**
	 * Account sign in.
	 *
	 * @return View
	 */
	public function getSignin()
	{
		// Is the user logged in?
		if (Sentry::check()) {
			return Redirect::to('home');
		}

		// Show the page
		$this->layout->content = View::make('auth.signin');
	}

	/**
	 * Account sign in form processing.
	 *
	 * @return Redirect
	 */
	public function postSignin()
	{
		// Declare the rules for the form validation
		$rules = array(
			'email'    => 'required|email',
			'password' => 'required|between:3,32',
		);

		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $rules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails()) {
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($validator);
		}

		try {
			// Try to log the user in
			$user = Sentry::authenticate(Input::only('email', 'password'), Input::get('remember-me', 0));
			
			/*
			if ( ! $this->isValid($user)) {
				Sentry::logout();
				return Redirect::back()->with('error', 'Your subscription has expired');
			}
			*/

			// Redirect to the users page
			return Redirect::to('home');
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
			$this->messageBag->add('email', 'No account was associated with your email address.');
		}
		catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
			$this->messageBag->add('email', 'Account is not yet active. Please check your email for instructions on activating your account.');
		}
		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
			$this->messageBag->add('email', Lang::get('auth/message.account_suspended'));
		}
		catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
			$this->messageBag->add('email', Lang::get('auth/message.account_banned'));
		}

		// Ooops.. something went wrong
		return Redirect::back()->withInput()->withErrors($this->messageBag);
	}

	/**
	 * Account sign up.
	 *
	 * @return View
	 */
	public function getSignup()
	{
		// Is the user logged in?
		if (Sentry::check()) {
			return Redirect::route('account');
		}

		// Show the page
		$this->layout->content = View::make('auth.signup');
	}

	/**
	 * Account sign up form processing.
	 *
	 * @return Redirect
	 */
	public function postSignup()
	{
		$st = implode(',', array_keys($this->subscription_types));
		// Declare the rules for the form validation
		$rules = array(
			'first_name'	=> 'required',
			'last_name'	=> 'required',
			'email'		=> 'required|email|unique:users',
			'password'	=> 'required|between:3,32',
	//		'subscription'	=> "required|in:{$st}"
		);
		$input = array_except(Input::all(), array('token'));

		// Create a new validator instance from our validation rules
		$validator = Validator::make($input, $rules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails()) {
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($validator);
		}

		try {
			// Register the user
	//		$input['valid_until'] = $this->getValidUntil($input['subscription']);
			$user = Sentry::register($input);
			$this->paid($user->id);

	//		$this->pay($input['subscription'], $user);
	//		// Redirect to the register page
			return Redirect::to('signin')->with('success', 'Your account has been successfully created. Please check your email for instructions on how to activate your account.');
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e) {
			$this->messageBag->add('email', 'An account with that email address already exists.');
		}

		// Ooops.. something went wrong
		return Redirect::back()->withInput()->withErrors($this->messageBag);
	}

	/**
	 * User account activation page.
	 *
	 * @param  string  $actvationCode
	 * @return
	 */
	public function getActivate($activationCode = null)
	{
		// Is the user logged in?
		if (Sentry::check())
		{
			return Redirect::route('account');
		}

		try
		{
			// Get the user we are trying to activate
			$user = Sentry::getUserProvider()->findByActivationCode($activationCode);

			// Try to activate this user account
			if ($user->attemptActivation($activationCode))
			{
				// Redirect to the login page
				return Redirect::route('signin')->with('success', 'Your account has been successfully activated. Please login using your email and password.');
			}

			// The activation failed.
			$error = 'Your account cannot be activated';
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
			$error = 'There was a problem activating your account. Please contact support.';
		}

		// Ooops.. something went wrong
		return Redirect::route('signin')->with('error', $error);
	}

	/**
	 * Forgot password page.
	 *
	 * @return View
	 */
	public function getForgotPassword()
	{
		// Show the page
		return View::make('frontend.auth.forgot-password');
	}

	/**
	 * Forgot password form processing page.
	 *
	 * @return Redirect
	 */
	public function postForgotPassword()
	{
		// Declare the rules for the validator
		$rules = array(
			'email' => 'required|email',
		);

		// Create a new validator instance from our dynamic rules
		$validator = Validator::make(Input::all(), $rules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails()) {
			// Ooops.. something went wrong
			return Redirect::route('forgot-password')->withInput()->withErrors($validator);
		}

		try {
			// Get the user password recovery code
			$user = Sentry::getUserProvider()->findByLogin(Input::get('email'));

			// Data to be used on the email view
			$data = array(
				'user'              => $user,
				'forgotPasswordUrl' => URL::route('forgot-password-confirm', $user->getResetPasswordCode()),
			);

			// Send the activation code through email
			Mail::queue('emails.forgot-password', $data, function($m) use ($user)
			{
				$m->to($user->email, $user->first_name . ' ' . $user->last_name);
				$m->subject('Account Password Recovery');
			});
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
			// Even though the email was not found, we will pretend
			// we have sent the password reset code through email,
			// this is a security measure against hackers.
		}

		//  Redirect to the forgot password
		return Redirect::route('forgot-password')->with('success', 'An email has been sent to recover your password.');
	}

	/**
	 * Forgot Password Confirmation page.
	 *
	 * @param  string  $passwordResetCode
	 * @return View
	 */
	public function getForgotPasswordConfirm($passwordResetCode = null)
	{
		try
		{
			// Find the user using the password reset code
			$user = Sentry::getUserProvider()->findByResetPasswordCode($passwordResetCode);
		}
		catch(Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			// Redirect to the forgot password page
			return Redirect::route('forgot-password')->with('error', 'No account was found.');
		}

		// Show the page
		return View::make('frontend.auth.forgot-password-confirm');
	}

	/**
	 * Forgot Password Confirmation form processing page.
	 *
	 * @param  string  $passwordResetCode
	 * @return Redirect
	 */
	public function postForgotPasswordConfirm($passwordResetCode = null)
	{
		// Declare the rules for the form validation
		$rules = array(
			'password'         => 'required',
			'password_confirm' => 'required|same:password'
		);

		// Create a new validator instance from our dynamic rules
		$validator = Validator::make(Input::all(), $rules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails()) {
			// Ooops.. something went wrong
			return Redirect::route('forgot-password-confirm', $passwordResetCode)->withInput()->withErrors($validator);
		}

		try {
			// Find the user using the password reset code
			$user = Sentry::getUserProvider()->findByResetPasswordCode($passwordResetCode);

			// Attempt to reset the user password
			if ($user->attemptResetPassword($passwordResetCode, Input::get('password')))
			{
				// Password successfully reseted
				return Redirect::route('signin')->with('success', Lang::get('auth/message.forgot-password-confirm.success'));
			}
			else
			{
				// Ooops.. something went wrong
				return Redirect::route('signin')->with('error', Lang::get('auth/message.forgot-password-confirm.error'));
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
			// Redirect to the forgot password page
			return Redirect::route('forgot-password')->with('error', Lang::get('auth/message.account_not_found'));
		}
	}

	/**
	 * Logout page.
	 *
	 * @return Redirect
	 */
	public function getLogout()
	{
		// Log the user out
		Sentry::logout();

		// Redirect to the users page
		return Redirect::route('signin');
	}
	
	public function checkAuth()
	{
		return Sentry::check() ? 1 : 0;
	}

	public function cancelPayment($user_id)
	{
		$user = User::find($user_id);
		// @todo maybe can save the data to pass as with input
		if ($user) {
			$user->delete();
		}

		return Redirect::route('signup');
	}

	public function paid($user_id)
	{
		$user = User::find($user_id);

		if ( ! $user) {
			//@ todo throw an error
			return Redirect::route('signup')->with('error', 'User not found');
		}

		$data = array(
			'user'          => $user,
			'activationUrl' => URL::route('activate', $user->getActivationCode()),
			'product'	=> 'Product Name',
		);

		// Send the activation code through email
		Mail::send('emails.register-activate', $data, function($m) use ($user)
		{
			$m->to($user->email, $user->first_name . ' ' . $user->last_name);
			$m->subject('Welcome ' . $user->first_name);
		});

		return Redirect::route('signup')->with('success', 'Your account has been successfully created. Please check your email for instructions on how to activate your account.');
	}

	protected function isValid($user)
	{
		return strtotime($user->valid_until) >= strtotime(date('Y-m-d'));
	}

	protected function getValidUntil($subscription)
	{
		$now = strtotime(date("Y-m-d"));

		if (in_array($subscription, array('standard_monthly', 'professional_monthly'))) {
			return date("Y-m-d", strtotime("+1 month", $now));
		}

		if (in_array($subscription, array('standard_annually', 'professional_annually'))) {
			return date("Y-m-d", strtotime("+1 year", $now));
		}
	}

	protected function pay($subscription, $user)
	{
		$amount = $this->subscription_types[$subscription];
		$gateway = new GatewayFactory();
		$gateway = $gateway->create('PayPal_Express');

		$gateway->setUsername('sandbox_dxc_bus_api1.test.com'); 
		$gateway->setPassword('1392007298'); 
		$gateway->setSignature('AiPC9BjkCyDFQXbSkoZcgqH3hpacAHEN5-o4LIjnhECPR825QdHT95XE');
		$gateway->setTestMode('true');

		$subscription = ucwords(str_replace('_', ' ', $subscription));
		$paypal_data = array(
			'amount' 	=> $amount,
			'description'	=> "BizPlannerPro {$subscription} Purchase",
			'returnUrl'	=> route('paid', array($user->id)),
			'cancelUrl'	=> route('cancel_payment', array($user->id)),
			'currency'	=> $this->subscription_currency
		);

		try {
			$response = $gateway->purchase($paypal_data)->send();
			if ($response->isSuccessful()) {
				// mark order as complete
				$responsereturn = $response->getData();
			} 
			elseif ($response->isRedirect()) {
				$response->redirect();
			} 
			else {
				// display error to customer
				exit($response->getMessage());
			}
		} 
		catch (Exception $e) {
			exit($e->getMessage());
			// internal error, log exception and display a generic message to the customer
		//	exit('Sorry, there was an error processing your payment. Please try again later.');
		}
	}
	

}




