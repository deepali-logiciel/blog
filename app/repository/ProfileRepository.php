<?php
namespace app\repository;
use DB;
use Illuminate\Support\Facades\Auth;
use user_profile;

class ProfileRepository
{


    public function image($filename ,$url)
    {
        $profile  = new user_Profile;
        $profile->user_id = Auth::Id();
        $profile->profile = $url;
        $profile->save();
        return $profile;
    
    }
  
}
?>