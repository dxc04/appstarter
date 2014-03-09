<?php

class HomeController extends BaseController {


	public function remunerations() 
	{
		$form_data = array();		
		$this->layout->content = View::make("data_entry", $form_data);
	}

	public function register()
	{
		return View::make('home.register');
	}

	public function login()
	{
		return View::make('home.login');
	}

	public function postRegister()
	{

		$input = Input::all();

		$rules = array('email' => 'required', 'password' => 'required');

		$validator = Validator::make($input, $rules);

		if($v->fails())
		{

			return Redirect::to('login')->withErrors($v);

		} else { 

			$credentials = array('email' => $input['email'], 'password' => $input['password']);

			if(Auth::attempt($credentials))
			{

				return Redirect::to('admin');

			} else {

				return Redirect::to('login');
			}
		}		

	}

	public function postLogin()
	{
		$credentials = array(
			'email'		=> Input::get('email'),
			'password'	=> Input::get('password'),
		);

		try {
			$user = Sentry::authenticate($credentials, false);
			if ($user) {
				return Redirect::to('home');
			}
		}
		catch (\Exception $e) {
			return Redirect::to('login')->withErrors(array('login' => $e->getMessage()));
		}
	}

	public function logout()
	{
		Sentry::logout();
		return Redirect::to('login')
	}

}
