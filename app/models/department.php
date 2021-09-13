<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class department extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'department';


	

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	public function users()
	{
		return $this->belongsToMany(User::class, 'user_department');
	}

}
