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
    Route::get('/admin/deleteUserQueue/{id}','AdminController@DeleteUserQueue');

        // activity
    Route::get('/admin/activities','AdminController@Activities');
    Route::get('/admin/activity/{id}','AdminController@Activity');
    Route::get('/admin/newActivity','AdminController@NewActivity');
    Route::post('/admin/newActivity','AdminController@PostNewActivity');
    Route::get('/admin/deleteActivities/{id}','AdminController@DeleteActivity');
    Route::get('/admin/userList/{id}','AdminController@QueueUserList');
    Route::get('/admin/allActivities','AdminController@AllActivities');
    Route::get('/admin/editActivity/{id}','AdminController@editActivity');
    Route::post('/admin/editActivity','AdminController@UpdateActivity');

        //check
    Route::get('/admin/userList/{id}/user/{userqueue_id}','AdminController@AcceptUser');
    Route::get('/admin/removeAccepted/{id}/user/{userqueue_id}','AdminController@removeAccepted');

    // User
    Route::get('/reserve/{q_id}','MainController@Reserve');
    Route::get('/profile','MainController@Profile');
    Route::get('/editprofile','MainController@EditProfile');
    Route::post('/editprofile','MainController@UpdateProfile');
    Route::post('/reserve/{q_id}','MainController@PostReserve');

    /**** NEW APIs FOR ANGULAR ****/

    /**** View ****/
    
    Route::get('index','ViewController@index');

    /***** User [Authenticated] *****/
    Route::get('/User/getQueues','UserController@getQueues');
    Route::get('/User/getAcceptedQueues','UserController@getAcceptedQueues');
    Route::get('/User/getProfile','UserController@getProfile');
    Route::post('/User/updateProfile','UserController@updateProfile');
    Route::get('/User/Reserve/{id}','UserController@getReserve');
    Route::post('/User/Reserve','UserController@Reserve');

    /***** App [guest APIs] *****/
    Route::get('/App/getQueues','AppController@getQueues');
    Route::get('/App/getQueueType','AppController@getQueueType');
    Route::get('/App/getQueue/{id}','AppController@getQueue');
    Route::get('/App/getPassedQueues','AppController@getPassedQueues');
    Route::get('/App/getRunningQueues','AppController@getRunningQueues');
    Route::get('/App/getActiveQueues','AppController@getActiveQueues');

    /***** Admin *****/
    Route::get('/Admin/getUsers','AdminController@getUsers');
    Route::get('/Admin/getUser/{id}','AdminController@getUser');
    Route::get('/Admin/getUserReserved/{id}','AdminController@getUserReserved');
    Route::get('/Admin/getUserHistory/{id}','AdminController@getUserHistory');
    Route::post('/Admin/deleteUser','AdminController@deleteUser');

    Route::get('/Admin/getQueues','AdminController@getQueues');


});
