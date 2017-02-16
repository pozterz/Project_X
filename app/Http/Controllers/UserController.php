<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Gate;
use App\Http\Requests;
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
		$this->validate($request, [
			'id' => 'required',
			'g-recaptcha-response'=>'required|captcha',
		]);

		return "KUY";
		
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

	private function isFull($Qid){
		$mainqueue = MainQueue::find($Qid);
		$count = $mainqueue->userqueue->count();
		if($mainqueue->current_count != $count && $mainqueue->current_count+$count <= $mainqueue->max){
			$mainqueue->current_count = $count;
			$mainqueue->save();
		}
		if($mainqueue->current_count < $mainqueue->max && $count < $mainqueue->max){
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
