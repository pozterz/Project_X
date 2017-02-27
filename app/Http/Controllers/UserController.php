<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\MainQueue;
use App\UserQueue;
use Carbon\Carbon;
use Auth;
use Gate;
use App\Http\Requests;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
	public function __construct(){
		if(Gate::denies('isUser',Auth::user())){
			abort(403);
  	}
  }

	/**
	 * [input {none}]
	 * @return [json] [all user's queue]
	 */
	public function getQueues(){
		$Queues = Auth::user()->userqueue;
		foreach ($Queues as $key => $Queue) {
			$Queue['captcha_key'] = $Queue->getQueue_captcha();
			$Queue->mainqueue;
			$Queue->mainqueue[0]->Queuetype;
		}
		return response()->json([
				'status' => 'Success',
				'result' => $Queues,
			]);
	}

	/**
	 * [getAcceptedQueues {none}]
	 * @return [json] [Accepted Queue]
	 */
	public function getAcceptedQueues(){
		$AcceptedQueues = Auth::user()->userqueue()->where('isAccept','yes')->get();
		foreach ($AcceptedQueues as $key => $Queue) {
			$Queue['captcha_key'] = $Queue->getQueue_captcha();
		}
		return response()->json([
				'status' => 'Success',
				'result' => $AcceptedQueues,
			]);
	}

	public function getReserve($id){
		return view('main.reserve',compact('id'));
	}

	public function Reserve(Request $request){
		$result = 'Failed';

		$validator = Validator::make($request->all(), [
			'id' => 'required',
			'g-recaptcha-response'=>'required|captcha',
		]);

		if($validator->fails()){
			$request->session()->flash('success','Please Validate Captcha');
			return back();
    }

		try{
			$userid = Auth::user()->id;
			$id = $request->get('id');
			$mainqueue = MainQueue::find($id);
			$userq = $mainqueue->userqueue->contains('user_id',$userid);
			$last = $mainqueue->userqueue->last();
			$current_count = $mainqueue->userqueue->count();
			if($mainqueue->close >= Carbon::now() && $mainqueue->start <= Carbon::now()){
				if(!$userq){
					if($current_count < $mainqueue->max){
						if($this->isInRange($mainqueue,$request)){
							if($request->get('reserve_minutes') <= $mainqueue->max_minutes){
								if(!$this->isOverlap($mainqueue,$request)){
									$cap = str_random(12);
									$start = $this->ConvertDate($request->get('reserve_start'),$request->get('reserve_start_time'));
									$reserved_min = $request->get('reserve_minutes');
									$createduq = UserQueue::create([
										"user_id" => $userid,
										"captcha" => $cap,
										"time" => $start,
										"reserved_min" => $reserved_min,
										"ip" => $request->get('ip'),
										]);
									$mainqueue = MainQueue::find($id);
									$mainqueue->userqueue()->attach($createduq->id);
									$request->session()->flash('success','Reserved Success.');
									return redirect('/index');
								}else{
									$request->session()->flash('success','overlap.');
									return back();
								}
							}else{
								$request->session()->flash('success','Minutes value must less than Service time/queue');
								return back();
							}
						}else{
							$request->session()->flash('success','Out of service time range');
							return back();
						}
					}else{
						$request->session()->flash('success','This service is full.');
						return back();
					}
				}else{
					$request->session()->flash('success','Already reserved this queue.');
					return back();
				}
			}else{
				$request->session()->flash('success','This activity isn\'t begin.');
				return back();
			}
		}catch(ModelNotFoundException $ex) {
			return response()->json([
    		'status' => $result,
				'result' => '',
			]);
		}
		
	}

	/**
	 * [getProfile description]
	 * @return [json] [user profile]
	 */
	public function getProfile(){
		$user = Auth::user();
		$user->user_info;
		return response()->json([
				'status' => 'Success',
				'result' => $user,
			]);
	}

	public function updateProfile(Request $request){
		$id = Auth::user()->id;
		$result = 'Failed';

		try{
			$user = User::find($id);
			$user->username = $request->get('username');
	    $user->name = $request->get('name');
	    $user->tel = $request->get('tel');
	    $user->save();
	    $result = 'Success';
		}catch(ModelNotFoundException $ex) {

		}
   

    return response()->json([
    		'status' => $result,
				'result' => '',
			]);
	}

	public function Upload($id,Request $request){
		$result = 'Success';
		$filename = Auth::user()->username.'_'.$id.'_'.Carbon::now()->timestamp.'.'.$request->file->extension();
		$path = $request->file('file')->move('files', $filename);
		return response()->json([
    		'status' => $result,
				'result' => $filename,
			]);
	}

	private function isOverlap($mainqueue,$request){
		$service_start = Carbon::parse($mainqueue->service_start);
		$service_end = Carbon::parse($mainqueue->service_end);
		$reserve_start = Carbon::parse($request->get('service_start'));
		$reserve_end = $reserve_start->addMinutes($request->get('reserve_minutes'));
		return max($service_start,$reserve_start) < min($service_end,$reserve_end);

	}

	private function isInRange($mainqueue,$request){
		$start = $this->ConvertDate($request->get('reserve_start'),$request->get('reserve_start_time'));
		if($start >= $mainqueue->service_start && $start <= Carbon::parse($mainqueue->service_end)->subMinutes($request->get('reserve_minutes'))) return true;
		return false;
	}

	private function toCarbon($time){
		return Carbon::parse($time);
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
