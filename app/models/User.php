<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;

class User extends SentryUserModel implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function getUserType()
	{
		if ( ! is_null($this->asAccountant())) {
			return 'accountant';
		}

		if ( ! is_null($this->asClient())) {
			return 'client';
		}

		return NULL;
	}

	public function asAccountant()
	{
		return Accountant::where('user_id', '=', $this->id)->first();
	}

	public function asClient()
	{
		return Client::where('user_id', '=', $this->id)->first();
	}

	public function getFullName()
	{
		return "{$this->first_name} {$this->last_name}";
	}

	public function client()
	{
		// @todo does not work
		return $this->belongsTo('Client');
	}

	public function accountant()
	{
		// @todo does not work
		return $this->belongsTo('Accountant');
	}

	public function isAccountant()
	{
		return ! is_null($this->asAccountant());
	}

	public function isClient()
	{
		return ! is_null($this->asClient());
	}

	public static function findPracticeProUser($id) 
	{
		return User::where('practicepro_user_id', $id)->first();
	}


}
