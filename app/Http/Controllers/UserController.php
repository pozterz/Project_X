<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\MainQueue;
use App\UserQueue;
use Carbon\Carbon;
use Auth;
use Gate;
use Mail;
use App\Http\Requests;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
	public function __construct(){
		if(Gate::denies('isModerator',Auth::user())){
			if(Gate::denies('isUser',Auth::user())){
				abort(403);
	  	}
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
			$Queue->mainqueue[0]->user;
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
		$id = $request->get('id');
		$mainqueue = MainQueue::find($id);
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
			$current_count = $mainqueue->userqueue->count();
			// if open close in range date 
			if($mainqueue->close >= Carbon::now() && $mainqueue->open <= Carbon::now()){
				// if not reserved
				if(!$userq){
					// if queue not full
					if($current_count < $mainqueue->max){
						// if reserve in range
						if($this->isInRange($mainqueue,$request)){
							// if reserve min < max
							if($request->get('reserve_minutes') <= $mainqueue->max_minutes){
								// if not overlap with other reserved
								if(!$this->isOverlap($mainqueue,$request)){
										$cap = str_random(9);
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
										$this->sendMail($createduq,$mainqueue);
										$request->session()->flash('success','Reserved Success.');
										return redirect('/index');
									
								}else{
									// check other queue with the same type
									if($this->reserveOtherQueue($mainqueue,$request)){
										$request->session()->flash('success','Reserved Success (With auto assigned).');
										return redirect('/index');
									}
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
	    $user->email = $request->get('email');
	    $user->tel = $request->get('phone');
	    $user->counter_id = $request->get('counter_id');
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
		foreach ($mainqueue->userqueue as $key => $queue) {
			$another_start = Carbon::parse($queue->time);
			$another_end = Carbon::parse($queue->time)->addMinutes($queue->reserved_min);
			$reserve_start = Carbon::parse($this->ConvertDate($request->get('reserve_start'),$request->get('reserve_start_time')));
			$reserve_end = Carbon::parse($this->ConvertDate($request->get('reserve_start'),$request->get('reserve_start_time')))->addMinutes($request->get('reserve_minutes'));
			if(max($another_start,$reserve_start) < min($another_end,$reserve_end)){
				return true;
			}
		}
		
		return false;

	}

	private function reserveOtherQueue($mainqueue,$request){
		$userid = Auth::user()->id;
		$queues = MainQueue::where('queuetype_id',$mainqueue->queuetype_id)
			->where('id','!=',$mainqueue->id)
			->where('close', '>=', Carbon::now())
			->where('open', '<=', Carbon::now())
			->get();

		foreach ($queues as $key => $other) {
			if($this->isInRange($other,$request)){
				$userq = $other->userqueue->contains('user_id',$userid);
				$current_count = $other->userqueue->count();
				if(!$userq){
					// if queue not full
					if($current_count < $other->max){
						// if reserve min < max
						if($request->get('reserve_minutes') <= $other->max_minutes){
							// if not overlap with other reserved
							if(!$this->isOverlap($other,$request)){
								$cap = str_random(9);
								$start = $this->ConvertDate($request->get('reserve_start'),$request->get('reserve_start_time'));
								$reserved_min = $request->get('reserve_minutes');
								$createduq = UserQueue::create([
									"user_id" => $userid,
									"captcha" => $cap,
									"time" => $start,
									"reserved_min" => $reserved_min,
									"ip" => $request->get('ip'),
									]);
								$reserve = MainQueue::find($other->id);
								$reserve->userqueue()->attach($createduq->id);
								$this->sendMail($createduq,$mainqueue);
								return true;
							}
						}
					}
				}
			}
		}
		return false;

	}

	private function isInRange($mainqueue,$request){
		$start = $this->ConvertDate($request->get('reserve_start'),$request->get('reserve_start_time'));
		if($start >= $mainqueue->service_start && $start <= Carbon::parse($mainqueue->service_end)->subMinutes($request->get('reserve_minutes')))
			return true;
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

    private function sendMail($data,$mainqueue){

      Mail::send('layouts.mail', ['data' => $data,'queue' => $mainqueue], function ($m)  {
          $m->from('pozterz2@gmail.com', 'Queue System Auto Mail');

          $user  = User::find(Auth::user()->id);

          $m->to($user->email)->subject('Reserved complete.');
      });

      return true;
    }

}
