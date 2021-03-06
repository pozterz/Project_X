<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Redirect;
use Session;
use Validator;
use Gate;
use App\User;
use App\UserInformation;
use App\UserQueue;
use App\MainQueue;
use App\QueueLog;
use App\Http\Requests;

class MainController extends Controller
{
	public function Index(){
		//dd(Auth::user()->userqueue);
		if(Auth::check()){
			$user_queue = Auth::user()->userqueue()->orderBy('queue_time')->get();
		}
		else
			$user_queue = null;

		$mainqueue = MainQueue::where('workingtime','>',Carbon::now()->toDateTimeString())->orderBy('close','asc')->paginate(10);
		
		$passedqueue = MainQueue::where('close','<',Carbon::now()->toDateTimeString())->where('workingtime','<',Carbon::now()->toDateTimeString())->orderBy('close','desc')->paginate(10);

		return view('main.index',compact('user_queue','mainqueue','passedqueue'));
	}

	public function Profile(){
		return view('main.profile');
	}

	public function EditProfile(){
		$user = Auth::user();
		return view('main.edit',compact('user'));
	}
	public function UpdateProfile(Request $req){
		$this->validate($request, [
			'username' => 'required|max:255|min:6|unique:users',
      'password' => 'required|confirmed|min:6',
      'name' => 'required|max:255',
      'email' => 'required|email|max:255|unique:users',
      'phone' => 'required|min:10|max:10',
		]);

		$id = Auth::user()->id;
		$user = User::find($id);
    $user->name = $req->get('name');
    $user->email = $req->get('email');
    $user->phone = $req->get('tel');
    $user->counter_id = ($req->get('counter_id') !== null)?$req->get('counter_id'):0;
    $user->save();

    $req->session()->flash('success', 'Update Complete.');
    return Redirect('/profile');
	}

	public function Reserve($q_id){
		$mainqueue = MainQueue::find($q_id);
		return view('main.reserve',compact('mainqueue'));
	}

	public function PostReserve($id,Request $request){
		$this->validate($request, [
			'id' => 'required',
			'g-recaptcha-response'=>'required|captcha',
		]);
		$userid = Auth::user()->id;
		$mainqueue = MainQueue::find($id);
		$userq = $mainqueue->userqueue->contains('user_id',$userid);
		$last = $mainqueue->userqueue->last();
		if($last == null){
			$qt = $mainqueue->opentime;
		}else{
			$qt = Carbon::parse($last->queue_time)->addMinutes($mainqueue->service_time);
		}
		if($mainqueue->end >= Carbon::now() && $mainqueue->start <= Carbon::now()){
			if(!$userq){
				if(!$this->isFull($id)){
					$cap = str_random(12);
					$createduq = UserQueue::create([
						"user_id" => $userid,
						"queue_captcha" => $cap,
						"queue_time" => $qt,
						"ip" => $request->get('ip'),
						]);
					$mainqueue = MainQueue::find($id);
					$mainqueue->current_count+=1;
					$mainqueue->save();
					$mainqueue->userqueue()->attach($createduq->id);
					$request->session()->flash('success','Reserved Success.');
					return redirect('/index');
				}else{
					$request->session()->flash('success','This service is full.');
					return back();
				}
			}else{
				$request->session()->flash('success','Already reserved this service.');
				return back();
			}
		}else{
			$request->session()->flash('success','This activity isn\'t begin.');
			return back();
		}

	}

	private function isFull($Qid){
		$mainqueue = MainQueue::find($Qid);
		$count = $mainqueue->userqueue->count();
		if($mainqueue->current_count != $count && $mainqueue->current_count+$count <= $mainqueue->max_count){
			$mainqueue->current_count = $count;
			$mainqueue->save();
		}
		if($mainqueue->current_count < $mainqueue->max_count && $count < $mainqueue->max_count){
			return false;
		}
		return true;
	}

	private function captcha_gen($len = 10){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $len; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	private function ConvertDate($date,$time){
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
