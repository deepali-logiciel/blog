<?php
namespace app\services;
use Carbon\Carbon;
use DB;
use app\repository\ProfileRepository;


class profileservices
{

    function __construct(ProfileRepository $repo)
	{
		$this->repo = $repo;
	}
   
    public function profile($file)
    {
        
        $destinationPath = public_path().'/userprofile/';
        $filename        = str_random(6) . '.' . $file->getClientOriginalExtension();
        $url=  ('/userprofile/'). $filename;
        $uploadSuccess   = $file->move($destinationPath, $filename);
        $time = $this->repo->image( $filename,$url);
        return $time;
     
    }
  
}
?>