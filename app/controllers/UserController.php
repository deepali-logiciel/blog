<?php

use Sorskod\Larasponse\Larasponse;
use Illuminate\Support\Facades\Validator;
use LucaDegasperi\OAuth2Server\Authorizer;
use app\services\authservice;
use app\services\profileservices;
use transformer\UserTransformer;
use transformer\ProfileTransformer;
use transformer\DepartmentTransformer;
use department;
use User;
use user_profile;         
use user_department;         

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	 protected $authservice;
     protected $authorizer;

	function __construct(Larasponse $response,
	Request $request,
	profileservices $profileservices,
    Authorizer $authorizer,
    Authservice $authservice)


    {
		$this->request=$request;
        $this->response = $response;
        $this->authorizer = $authorizer;
        $this->authservice = $authservice;
		$this->services  = $profileservices;
        if(Input::get('includes')){
            $this->response->parseIncludes(Input::get('includes')); 
        }
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function upload()
	{
		$input = Input::all();
		$valid=[
			'profile'=> 'required|image|mimes:jpeg,png,jpg|max:2048',
		];


		$validation=Validator::make($input, $valid);
		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}


		$destinationPath = '';
        $filename        = '';

    if (Input::hasFile('profile')) {
        $file            = Input::file('profile');
		$log = $this->services->profile($file);
		// dd($log);
		return Response::json($this->response->item($log, new ProfileTransformer));
	
    }

	return Response::json('not found');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function signup()
	{
		$data = Request::all();
		$input = Input::all();
        $rules=[
            'email'=> 'required|unique:user',
            'password'=> 'required'
        ];

        $validation=Validator::make($input, $rules);

        if($validation->fails()){
            return Response::json($validation->errors(),412);
        }


        $add=new User;
        $add->first_name = $data['first_name'];
        $add->last_name = $data['last_name'];
        $add->email = $data['email'];
        $add->password = Hash::make($data['password']);
        $add->save();

        return Response::json([
         "message" => "SignUp Succesfully"
        ],200   );
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */


	public function status()
	{
		$input = Input::all();

		$rules=[
			'is_active'=> 'required',
		];
		$validation = Validator::make($input, $rules);

		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}

		$status = Request::get('is_active');


		if($status==0){
			return Response::json(["message" => "inactive"],201	);
		}


		else if($status==1){
			return Response::json(["message" => "active"],201);
		}
		

			return Response::json(["message" => "please enter valid boolean number"	],404	);
		
	}
	


		public function login()
        {
			$user= Input::all();
			$user = User::where('email' , Input::get('username'))->first();
			// dd($user);

			$token = $this->authorizer->issueAccessToken();
			// dd($token);

			$token = $this->authservice->verify($token);

			return Response::json([
				"message" => "Login Succesfully",
				$token
			],200   );
        }



	    public function userindex()
	    {
			$users = User::all();
			return Response::json($this->response->Collection($users, new UserTransformer));
	    }

	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */


	public function depindex()
	{
		$dep = department::all();
		return Response::json($this->response->Collection($dep, new DepartmentTransformer));
	}
 

	public function pivot()
	{
		// $users =user::first();
		// $users->department()->sync([1,2]);
		// return Response::json([
        //     "message" => " inserted Succesfully",
         
        //    ],200   );

        $user = User::find(Input::get('user_id'));
		$depIds = Input::get('department_id');

		$user->department()->sync((array)$depIds);

		return Response::json([
			    "message" => "inserted Succesfully",
			 
			   ],200   );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	
}
