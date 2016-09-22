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
    Route::get('/index','MainController@Index');
    // Admin
        // user
    Route::get('/admin','AdminController@Index');
    Route::get('/admin/users','AdminController@ManageUser');
    Route::get('/admin/user/{id}','AdminController@GetUser');
    Route::get('/admin/AddUser','AdminController@NewUser');
    Route::post('/admin/AddUser','AdminController@PostNewUser');
    Route::get('/admin/edit/{id}','AdminController@EditUser');
    Route::post('/admin/PostEdit/{id}','AdminController@PostEdit');
    Route::get('/admin/userqueue/{id}','AdminController@ViewUserQueue');
    Route::get('/admin/delete/{id}','AdminController@DeleteUser');
<<<<<<< HEAD
    Route::get('/admin/deleteUserQueue/{id}','AdminController@DeleteUserQueue');
    
=======
    Route::get('/admin/deleteUserQueue/{uq_id}','AdminController@DeleteUserQueue');
>>>>>>> master
        // activity
    Route::get('/admin/activities','AdminController@Activities');
    Route::get('/admin/newActivity','AdminController@NewActivity');
    Route::post('/admin/newActivity','AdminController@PostNewActivity');
    Route::get('/admin/deleteActivities/{id}','AdminController@DeleteActivity');
    // User
    Route::get('/reserve/{q_id}','MainController@Reserve');
    Route::get('/profile','MainController@Profile');
    Route::post('/reserve/{q_id}','MainController@PostReserve');
});
