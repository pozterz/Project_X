<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Input;
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
    public function ManageUser(){
        $users = User::paginate(20);
        return view('admin.User-panel',compact('users'));   
    }
    public function GetUser($id){
        $user = User::find($id);
        return view('admin.user',compact('user'));
    }
    public function EditUser($id){
        $user = User::find($id);
        return view('admin.edituser',compact('user'));
    }
    public function PostEdit(Request $req){
        $user = User::find();
        echo $user;
    }
    public function DeleteUser($id){
        $user = User::find($id);
        $user->delete();
        return Redirect('/admin/users');
    }
}
