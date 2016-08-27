<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Illuminate\Http\Request;

Route::get('/', function () {
   return redirect('/index');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('progress','ProgressController@index');

use App\MainQueue;
Route::get('/test2', function () {
	$mainqueue = MainQueue::where('status','ready')->orWhere('status','begin')->orderBy('start','asc')->paginate(10);
    return view('material.index2',compact('mainqueue'));
});
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/index', 'MainController@index');
});
