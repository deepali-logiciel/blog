<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class post extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	protected $fillable = [
        'title', 'description'
    ];
	protected $hidden = [
        // 'created_at', 'updated_at'
    ];

	public $primarykey = 'id';
    public $timestamps = 'true';

	// public function user(){
	// 	return $this->belongTo(user::class);
	// 	// return $this->hasMany('comment');
	// }

	public function User(){

		return $this->belongsTo('App\User')->select(['id', 'first_name',]);
	  }

	public function comment(){
		return $this->hasMany(comment::class);
		// return $this->hasMany('comment');
	}

	
	public function users () {
		return $this->belongsTo(User::class,'user_id','id');
	  }


	  public function marked () {
		return $this->belongsTo(User::class,'marked_by','id');
	  }
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	

}
