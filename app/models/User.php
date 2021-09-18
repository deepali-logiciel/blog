<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


	public function post(){

		return $this->hasMany(post::class);
    }

	public function comment(){
		return $this->hasMany(comment::class);
		
	}

	public function profile(){

		return $this->hasOne(user_profile::class);
		
	}

	public function department()
	{
		return $this->belongsToMany(department::class, 'user_department');
	}


	public function by(){

		return $this->hasMany(post::class);
    }
	
}
