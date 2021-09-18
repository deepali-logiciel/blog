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
Route::post('/posts','PostsController@poststore');
Route::get('/posts','PostsController@postindex');
Route::get('posts/{title}/Postsdisplay','PostsController@Postdisplay');
Route::put('posts/{id}','PostsController@postedit');
Route::delete('/posts/{id}','PostsController@postdelete');
Route::get('/posts/{id}','PostsController@displaybyid');
Route::post('/favourite','PostsController@favourite');

    });

      ///////-----comments------///////////

 Route::group(['before' => 'oauth'], function()
      {
Route::post('/comments','PostsController@commentstore');
Route::get('/comments','PostsController@commentindex');
Route::delete('/comments/{id}','PostsController@commentdelete');
Route::put('comments/{id}','PostsController@commentedit');
});

///////----user-----//////

Route::post('/signup','UserController@signup');
Route::post('/login','UserController@login');
Route::post('/status','UserController@status');
Route::get('/userindex','UserController@userindex');

Route::group(['before' => 'oauth'], function()
      {
    Route::post('/upload','UserController@upload');
});
 
////////-------department------////////

Route::get('/department','UserController@depindex');
Route::post('/pivot','UserController@pivot');


