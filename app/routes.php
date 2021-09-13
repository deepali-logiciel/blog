<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('/', function()
{
    echo "welcome to blog assignment..";
    exit;
});
Route::group(['before' => 'oauth'], function()
    {
Route::post('/post','PostController@poststore');
Route::get('/post','PostController@postindex');
Route::get('post/{title}/Postdisplay','PostController@Postdisplay');
Route::put('post/{id}/post','PostController@postedit');
Route::delete('/post/{id}','PostController@postdelete');
Route::get('/displaybyid/{id}','PostController@displaybyid');
Route::get('/indexx','PostController@indexx');
    });

      ///////-----comments------///////////

 Route::group(['before' => 'oauth'], function()
      {
Route::post('/comment','PostController@commentstore');
Route::get('/comment','PostController@commentindex');
Route::delete('/comment/{id}','PostController@commentdelete');
Route::put('comment/{id}/comment','PostController@commentedit');
});


///////----user-----//////


Route::post('/signupstore','UserController@signupstore');
Route::post('/login','UserController@login');
Route::post('/status','UserController@status');
Route::get('/userindex','UserController@userindex');

Route::group(['before' => 'oauth'], function()
      {
    Route::post('/upload','UserController@upload');
});
 

////////-------department------////////
Route::get('/depindex','UserController@depindex');
Route::post('/pivot','UserController@pivot');


