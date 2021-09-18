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


class PostsController extends \BaseController {
   
	
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
		$post = Post::query();
        $title = Request::get('title');
        $limit =  Request::get('limit');
		$login =  Request::get('login');
		$user_id = Request::get('user_id');
		$first_name = Request::get('first_name');
		$is_favourite = Request::get('is_favourite');
        if(!$limit){
            $limit = 10;
         }

		 if($user_id){
			$post->whereIn('user_id',$user_id);
		 }


		 if($first_name){
		    $post->leftJoin('user','posts.user_id', '=','user.id')->whereIn('first_name',$first_name);
		   }


		if($is_favourite){
			$post->whereIn('is_favourite',$is_favourite);
		}
		

		 $post->select('posts.*');
		 $posts = $post->paginate($limit);
		 $post = $this->sort($posts,$limit);
		 return Response::json($this->response->Paginatedcollection($posts, new TitleTransformer));

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
		$userId = Auth::id();
		$input = Input::all();
		
		$rules = [

			'title'=> 'required|unique:posts|max:20',
			'description'=> 'required|max:200'
		];
		$validation = Validator::make($input, $rules);

		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}

		$mark = post::where('is_favourite', '=', 1 );
        $post = new post;
		$messege = [
			['messege'=>'record inserted sucessfully'],
			[$post]
		];

		$post->id = Request::get('id');
		$post->user_id = $userId ;
        $post->title = Request::get('title');
        $post->description = Request::get('description');
		if($mark){
		$post->marked_by = $userId;
		$post->save();
		}
		
		$post->marked_by = 0;	
        $post->save();

		return $messege;

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */	
	


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postedit($id)
	{
		$input = Input::all();
		$rules = [
			'title' => 'required|max:20|unique:posts,title' . ($id ? ",$id" : ''),
			'description' => 'required|max:200'
		];
		$validation = Validator::make($input,$rules);
		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}


        $post = post::find($id);
		$messege = [
			['messege'=>'record updated sucessfully'],
			[$post]
		];

		

		if($post){
        $post->title = Request::get('title');
        $post->description = Request::get('description');
        $post->update();
		return $messege;

		}
		
			return Response::json(["meesege" => "record not found"],404);
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
		$post = post::find($id);
		
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

		$input = Input::all();
		$userId = Auth::id();

		$rules=[
			'post_id'=> 'required',
			'comment'=> 'required|max:200'
		];

		$validation=Validator::make($input, $rules);

		if($validation->fails()){
			return Response::json($validation->errors(),404);
		}

		$comment = new comment;
		$comment->user_id = $userId;
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
					$messege=[
					['messege'=>'record inserted sucessfully'],     
						[$comment]
					];
					return $messege;			
    }



	public function commentindex()
	{
		
        $post_id = Request::get('post_id');
        $limit =  Request::get('limit');

        if(!$limit){
            $limit = 10;
         }

		$data = comment::select("*")
		->where( 'post_id','LIKE',"%$post_id%")
		->paginate($limit);
        return Response::json($this->response->PaginatedCollection($data, new CommentTransformer));
	
	}

	public function commentdelete($id)
	{
		$comment = comment::find($id);
		
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
		$input=Input::all();
		$rules=[
			'comment' => 'required|max:200'
		];
		$validation = Validator::make($input,$rules);

		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}

        $comment = comment::find($id);
		$messege=[
			['messege' => 'record updated sucessfully'],
			[$comment]
		];

		
		if($comment){
        $comment->comment = Request::get('comment');
        $comment->update();
		return $messege;
		}
			return Response::json(["meesege"=>"record not found"],404);
	}

	public function favourite()
	{
		
		$add = Post::Find(Input::get('post_id'));

        if($add){
		$fav = Input::get('is_favourite');

 		if($fav == 0){
			$add->marked_by = 0;
			$add->is_favourite = $fav;
			$add->save();
			return Response::json([	"message" => "not a favourite post"],201);	
		}


		else if($fav == 1){
			$add->marked_by = Authorizer::getResourceOwnerId();
			$add->is_favourite=$fav;
			$add->save();
			return Response::json(["message" => "favourite post"],201);
	
		}
		
		else{
			return Response::json(["message" => "please enter valid boolean number"],404);

            
		}
	}

	}

}
