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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppController extends Controller
{
	/**
	 * [get all queues]
	 * @return [json] [all queue]
	 */
	public function getQueues(){
		$Queues = MainQueue::all();

		foreach ($Queues as $key => $Queue)
		{
			$Queue['current_count'] = $Queue->userqueue()->count();
		}

		return response()->json([
				'status' => 'Success',
				'result' => $Queues,
			]);
	}

	/**
	 * [get one queue]
	 * @return [json] [one queue contain current count and all reserved users]
	 */
	public function getQueue($id){

		$result = 'Failed';

		try
		{
			$Queue = MainQueue::findOrfail($id);
			$Queue['current_count'] = $Queue->userqueue()->count();
			$Queue->userqueue;
			$result = 'Success';
		}catch(ModelNotFoundException $ex) {
			return response()->json([
				'status' => $result,
				'result' => null,
			]);
		}

		return response()->json([
				'status' => $result,
				'result' => $Queue,
			]);

	}

	/**
	 * [Active Queue | end > now]
	 * @return [json] [description]
	 */
	public function getActiveQueues(){

		$Queues = MainQueue::where('end','>',Carbon::now()->toDateTimeString())->orderBy('end','asc')->get();

		foreach ($Queues as $key => $Queue) {
			$Queue['current_count'] = $Queue->userqueue()->count();
			$Queue->userqueue;
		}
		
		$result = 'Success';
		
		return response()->json([
				'status' => $result,
				'result' => $Queues,
			]);
	}

	/**
	 * [Passed Queue | end < now]
	 * @return [json] [passed queue]
	 */
	public function getPassedQueues(){

		$Queues = MainQueue::where('end','<',Carbon::now()->toDateTimeString())->where('opentime','<',Carbon::now()->toDateTimeString())->orderBy('end','desc')->get();

		foreach ($Queues as $key => $Queue) {
			$Queue['current_count'] = $Queue->userqueue()->count();
			$Queue->userqueue;
		}
		
		$result = 'Success';
		
		return response()->json([
				'status' => $result,
				'result' => $Queues,
			]);
	}


}
