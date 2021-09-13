<?php

use Sorskod\Larasponse\Larasponse;
use Illuminate\Support\Facades\Validator;
use post;
use User;
use comment;
use transformer\TitleTransformer;
use transformer\CommentTransformer;
use Traits\PostTrait;
use Illuminate\Support\Facades\Auth;


class PostController extends \BaseController {
   
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    // protected $response;
	use PostTrait;

    public function __construct(Larasponse $response)
    {
        $this->response = $response;
		if(Input::get('includes')){
			$this->response->parseIncludes(Input::get('includes'));
		}
    }

	public function postindex()
	{
		$add= Post::query();
        $title = Request::get('title');
        $limit =  Request::get('limit');
		$login =  Request::get('login');
		$user_id = Request::get('user_id');
		$first_name = Request::get('first_name');
        if(!$limit){
            $limit = 10;
         }

		 if($user_id){
			$data = Post::select("*")
				->whereIn('user_id',$user_id)
				->get();
			return Response::json($this->response->collection($data, new TitleTransformer));	
		 }
		 elseif($first_name){
			$name = Post::leftJoin('user','posts.user_id', '=','user.id')
			->whereIn('first_name',$first_name)
			->get();
			return Response::json($this->response->Collection($name, new TitleTransformer));
		   }
		
		 else{
		 	$add = Post::where('title','LIKE',"%$title%")->Paginate($limit);
			$add = $this->sort($add ,$limit);
			
			return Response::json($this->response->Paginatedcollection($add, new TitleTransformer));
		 }	
	}
	


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function poststore()
	{
		$id=Auth::id();
		
		$rules=[

			'title'=> 'required|unique:posts|max:20',
			'description'=> 'required|max:200'
		];
		$validation=Validator::make(Input::all(),$rules);

		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}

        $post=new post;
		$messege=array(
			array('messege'=>'record inserted sucessfully'),
			array($post)
		);
		$post->id = Request::get('id');
		$post->user_id = Authorizer::getResourceOwnerId();
        $post->title = Request::get('title');
        $post->description = Request::get('description');
        $post->save();

		return $messege;

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postedit($id)
	{
		$rules=[
			'title'=> 'required|max:20|unique:posts,title' . ($id ? ",$id" : ''),
			'description'=> 'required|max:200'
		];
		$validation=Validator::make(Input::all(),$rules);

		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}

        $post=post::find($id);
		$messege=array(
			array('messege'=>'record updated sucessfully'),
			array($post)
		);

		

		if($post){
        $post->title = Request::get('title');
        $post->description = Request::get('description');
        $post->update();
		return $messege;
		}
		
			return Response::json(["meesege"=>"record not found"],404);
	

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
	public function postdelete($id)
	{
		$post=post::find($id);
		
		if($post){
		$post->delete();
		return Response::json(["meesege"=>"record deleted sucessfully"],200);
		}
		else{
			return Response::json(["meesege"=>" Record not found "],404);
		}
		

		return Response::json($post);
	}



	public function displaybyid($id)
	{
		$transformer = post::find($id);
		// return (new PostTransformer)->transform($user);
		if($transformer){
			return $this->response->item( $transformer, new TitleTransformer);
		}
	
			return Response::json(["meesege"=>" Record not found "],404);
	}
	


	public function commentstore()
    {

	
		$id=Auth::id();
		// dd($id);
		$rules=[
			'post_id'=> 'required',
			'comment'=> 'required|max:200'
		];
		$validation=Validator::make(Input::all(),$rules);

		if($validation->fails()){
			return Response::json($validation->errors(),404);
		}
		$comment=new comment;
		$comment->user_id = Authorizer::getResourceOwnerId();
		$comment->post_id = Request::get('post_id');
		$comment->comment = Request::get('comment');
		if(input::get('parent_id')){
		$current = comment::where('id', '=', input::get('parent_id'))->first(); 
	
	
		if($current && ($n = $current->children) && ($n->children) ) {

		return Response::json(["meesege"=>"record is invalid"],400);
		}
		$comment->parent_id = Request::get('parent_id');			  	
		}
		
					$comment->save();
					$messege=array(
						array('messege'=>'record inserted sucessfully'),     
						array($comment)
					);
					return $messege;			
    }

	public function commentindex()
	{
		
        $post_id = Request::get('post_id');
        $limit =  Request::get('limit');
        if(!$limit){
            $limit = 10;
         }
		$data=comment::select("*")
		->where( 'post_id','LIKE',"%$post_id%")
		->paginate($limit);
        return Response::json($this->response->PaginatedCollection($data, new CommentTransformer));
	
	}

	public function commentdelete($id)
	{
		$comment=comment::find($id);
		
		if($comment){
		$comment->delete();
		return Response::json(["meesege"=>"record deleted sucessfully"],200);
		}
		else{
			return Response::json(["meesege"=>" Record not found "],404);
		}
		return Response::json($comment);
	}



	public function commentedit($id)
	{
		
		$rules=[
			'comment'=> 'required|max:200'
		];
		$validation=Validator::make(Input::all(),$rules);

		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}

        $comment=comment::find($id);
		$messege=array(
			array('messege'=>'record updated sucessfully'),
			array($comment)
		);

		

		if($comment){
        $comment->comment = Request::get('comment');
        $comment->update();
		return $messege;
		}
			return Response::json(["meesege"=>"record not found"],404);
	}

}
