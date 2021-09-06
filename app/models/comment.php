<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class comment extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'comment';

	protected $fillable = [
        'post_id', 'comment'
    ];
	protected $hidden = [
        'created_at', 'updated_at'
    ];

	

	public function post(){

		return $this->belongsTo(post::class);
    }

		public function user()
	{
		return $this->belongsTo(User::class)->select(['id', 'first_name']);
	}

	public function replies() 
	{
		return $this->hasMany(comment::class,'parent_id');
	}

	public function children() 
	{
		return $this->belongsTo(comment::class, 'parent_id');
		// ->with('replies');
	}

// public function children() {
// 	return $this->hasMany(comment::class, 'parent_id', 'id');
// }

// public function commentt() 
// {
//     return $this->belongsTo(comment::class, 'id');
// }




    // public function onepost()
    // {
    // 	return $this->belongsTo(post::class);
		
    // }
	
	

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	

}
