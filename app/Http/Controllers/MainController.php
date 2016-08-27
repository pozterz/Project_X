<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Mail;
use Redirect;
use App\User;
use App\UserInformation;
use App\UserQueue;
use App\MainQueue;
use App\QueueLog;
use App\Http\Requests;

class MainController extends Controller
{
    public function index(){
    	if(Auth::check()){
    		$user_queue = Auth::user()->userqueue;
    	}
	    else
    		$user_queue = null;
    	if(count($user_queue)){
	    	foreach ($user_queue as $key => $value) {
	    		$queue_detail[] = MainQueue::where('id',$value->queue_id)->get();
	    	}
    	}


    	$mainqueue = MainQueue::where('status','ready')->orWhere('status','begin')->orderBy('end','asc')->paginate(10);

    	return view('main.index',compact('user_queue','queue_detail','mainqueue'));
    }


}
