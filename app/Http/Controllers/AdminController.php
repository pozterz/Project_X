<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Validator;
use App\Http\Requests;
use App\User;
use App\MainQueue;
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
    public function PostEdit(Request $req,$id){
        $user = User::find($id);
        echo $user;
    }
    public function DeleteUser($id){
        $user = User::find($id);
        $user->delete();
        return Redirect('/admin/users');
    }

    public function Activities(){
        $mainqueues = MainQueue::all();
        return view('admin.Activity-panel',compact('mainqueues'));
    }

    public function NewActivity(){
        return view('admin.newActivity');
    }

    public function PostNewActivity(Request $request){
         $validator = Validator::make($request->all(),[
            'queue_name' => 'required|string|max:150',
            'counter' => 'required|string|max:100',
            'opentime' => 'required',
            'service_time' => 'required|integer',
            'start' => 'required',
            'end' => 'required',
            'max_count' => 'required|integer',
        ]);

        if($validator->fails()){
            return view('admin.newActivity')->withErrors($validator);
        }
        $Queue = new MainQueue;
        $Queue->queue_name = $request->get('queue_name');
        $Queue->counter = $request->get('counter');
        $Queue->opentime = $this->ConvertDate($request->get('opentime'),$request->get('opentime_time'));
        $Queue->service_time = $request->get('service_time');
        $Queue->start = $this->ConvertDate($request->get('start'),$request->get('start_time'));
        $Queue->end = $this->ConvertDate($request->get('end'),$request->get('end_time'));
        $Queue->current_count = 0;
        $Queue->max_count = $request->get('max_count');
        $Queue->owner = Auth::user()->id;
        $Queue->status = 'ready';

        $Queue->save();
        
        $request->session()->flash('success', 'Added new activity!');
        return Redirect('/admin');
    }

    public function ConvertDate($date,$time){
        $split = explode(':',$time);
        if(count($split) != 2){
            $split = array();
            $split[0] = 0;
            $split[1] = 0;
        }
        $end_time = Carbon::parse($date)
            ->startOfDay()
            ->addHours($split[0])
            ->addMinutes($split[1])
            ->toDateTimeString();
        return $end_time;
    }

}
