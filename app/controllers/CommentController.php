<?php

use Sorskod\Larasponse\Larasponse;
use Illuminate\Support\Facades\Validator;
use comment;
use transformer\TitleTransformer;

class CommentController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */



	public function __construct(Larasponse $response)
    {
        $this->response = $response;

        // The Fractal parseIncludes() is available to use here
		if(Input::get('includes')){
			$this->response->parseIncludes(Input::get('includes'));
		}
    }


	public function index1()
	{
		// $data = comment::all();
		// return $data;

		$data=DB::table('comment')
            ->join('posts', 'posts.id', '=', 'comment.post_id')
            ->get();

			return $data;
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
	
    public function store1()
    {
		$rules=[
			'post_id'=> 'required',
			'comment'=> 'required'
		];
		$validation=Validator::make(Input::all(),$rules);

		if($validation->fails()){
			return Response::json($validation->errors(),404);
		}

        $comment=new comment;
        $comment->post_id = Request::get('post_id');
        $comment->comment = Request::get('comment');
        $comment->save();


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
	public function show($id)
	{
		//
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
	public function destroy($id)
	{
		//
	}


}
