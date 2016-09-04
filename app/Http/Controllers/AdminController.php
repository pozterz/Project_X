<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests;
use App\User;
use Auth;
class AdminController extends Controller
{
	public function UserCheck(){
		if(Auth::guest()) return false;
    	if(Auth::user()->level != 'admin') return false;
    	return true;
	}
    public function Index(){
    	if(!$this->UserCheck()){
			return Redirect('/index');
    	}
    	return view('admin.index');
    }
    public function getCmd($cmd){

    }
}
