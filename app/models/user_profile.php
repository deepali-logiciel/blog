<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class user_profile extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_profile';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	public function User () {
		return $this->hasOne(User::class);
	  }

}
