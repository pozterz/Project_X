<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Redirect;
use Session;
use App\User;
use App\UserInformation;
use App\UserQueue;
use App\MainQueue;
use App\QueueLog;
use App\Http\Requests;

class MainController extends Controller
{
	public function Index(){
		if(Auth::check()){
			$user_queue = Auth::user()->userqueue()->where('queue_time','>',Carbon::now()->toDateTimeString())->get();
		}
		else
			$user_queue = null;

		if(count($user_queue)){
			foreach ($user_queue as $key => $value) {
				$queue_detail[] = MainQueue::where('id',$value->queue_id)->get();
			}
		}
		
		$mainqueue = MainQueue::where('end','>',Carbon::now()->toDateTimeString())->where(function($q){$q->where('status','ready')->orWhere('status','begin');})->orderBy('end','asc')->paginate(10);

		return view('main.index',compact('user_queue','queue_detail','mainqueue'));
	}

	public function Profile(){
		return view('main.profile');
	}

	public function Reserve($q_id){
		$mainqueue = MainQueue::find($q_id);
		$owner = User::find($mainqueue->owner);
		return view('main.reserve',compact('mainqueue','owner'));
	}

	public function PostReserve($id,Request $request){
		$this->validate($request, [
			'id' => 'required',
			'g-recaptcha-response'=>'required|captcha',
		]);

		$userid = Auth::user()->id;
		$userq = UserQueue::where('user_id',$userid)->where('queue_id',$id)->count();
		$last = UserQueue::where('queue_id',$id)->orderBy('id', 'desc')->first();
		if($last == null){
			$qt = MainQueue::find($id)->opentime;
		}else{
			$MainQueueData = MainQueue::find($id);
			$qt = Carbon::parse($MainQueueData->opentime)->addMinutes($MainQueueData->service_time);
		}
		if(!$userq){
			$cap = str_shuffle('acvobihzk');
			UserQueue::create([
				"queue_id" => $id,
				"user_id" => $userid,
				"queue_captcha" => $cap,
				"queue_time" => $qt,
				]);
			$request->session()->flash('success','Reserved Success.');
			return redirect('/index');
		}else{
			$request->session()->flash('success','Already reserved this service.');
			return back();
		}

	}

}
