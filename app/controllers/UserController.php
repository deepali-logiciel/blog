<?php

use Sorskod\Larasponse\Larasponse;
use Illuminate\Support\Facades\Validator;
use LucaDegasperi\OAuth2Server\Authorizer;
use app\services\authservice;
use transformer\UserTransformer;
use User;

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	 protected $authservice;
     protected $authorizer;

	function __construct(Larasponse $response,
    Authorizer $authorizer,
    Authservice $authservice)
    {
        $this->response = $response;
        $this->authorizer = $authorizer;
        $this->authservice = $authservice;
        // The Fractal parseIncludes() is available to use here
        if(Input::get('includes')){
            $this->response->parseIncludes(Input::get('includes'));
        }
    }




	public function Userindex()
	{
		// $posts = Post::with('comment.user')->get();

		// return ($posts);
		$all=User::with('post.comment')->get();

        
        $limit =  Request::get('limit');
        if(!$limit){
            $limit = 10;
         }
		
		    
        // $data = User::where('all','LIKE',"%$all%")->paginate($limit);
      
        // // $post=($limit==0) ? "10":"$limit";
      
        // return Response::json($this->response->paginatedCollection($data, new TitleTransformer));
		return $all;
	}




	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function signupstore()
	{
		$data=Request::all();
		$rules=[
			'email'=> 'required|unique:user|max:200',
			'password'=> 'required|max:200'
		];
		$validation=Validator::make(Input::all(),$rules);

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
			"message" => "inserted successfully"

			
		],201	);
	

	
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */


	public function status()
	{
	
		$rules=[
			'is_active'=> 'required',
		];
		$validation=Validator::make(Input::all(),$rules);

		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}

		$status = Request::get('is_active');

        // $add=new User;
		// $add->is_active = Request::get('is_active');
        // $add->save();

		if($status==0){
			return Response::json([
				"message" => "inactive"
	
				
			],201	);
		}
		else if($status==1){
			return Response::json([
				"message" => "active"
	
				
			],201	);
	
		}
		else{
			return Response::json([
				"message" => "please enter valid boolean number"
	
				
			],404	);
		}
		

		
	

	
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

	



	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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
