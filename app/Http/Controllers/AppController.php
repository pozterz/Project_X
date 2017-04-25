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
use App\UserQueue;
use App\MainQueue;
use App\QueueType;
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
			$Queue->QueueType;
			$Queue['current'] = $Queue->userqueue()->count();
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
			$Queue['current'] = $Queue->userqueue()->count();
			$Queue->user;
			$Queue->QueueType;
			foreach ($Queue->userqueue as $key => $user) {
				$user->user;
			}

			$result = 'Success';
		}catch(ModelNotFoundException $ex) {
			return response()->json([
				'status' => $result,
				'result' => null,
			]);
		}

		return response()->json([
				'status' => $result,
				'result' => array($Queue),
			]);

	}

	/**
	 * [Active Queue | end > now]
	 * @return [json] [description]
	 */
	public function getActiveQueues(){

		$Queues = MainQueue::where('close','>',Carbon::now()->toDateTimeString())->orderBy('close','asc')->get();

		foreach ($Queues as $key => $Queue) {
			$Queue->QueueType;
			$Queue['current'] = $Queue->userqueue()->count();
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

		$Queues = MainQueue::where('close','<',Carbon::now()->toDateTimeString())->where('service_start','<',Carbon::now()->toDateTimeString())->orderBy('close','desc')->get();

		foreach ($Queues as $key => $Queue) {
			$Queue->QueueType;
			$Queue['current'] = $Queue->userqueue()->count();
			$Queue->userqueue;
		}
		
		$result = 'Success';
		
		return response()->json([
				'status' => $result,
				'result' => $Queues,
			]);
	}

	/**
	 * [Running Queue | end < now]
	 * @return [json] [Running queue]
	 */
	public function getRunningQueues(){
		$Queues = MainQueue::where('service_start','<=',Carbon::now()->toDateTimeString())->where('service_end','>',Carbon::now()->toDateTimeString())->where('close','<',Carbon::now()->toDateTimeString())->orderBy('service_start','desc')->get();

		foreach ($Queues as $key => $Queue) {
			$Queue->QueueType;
			$Queue->user;
			$Queue['current'] = $Queue->userqueue()->count();
			foreach ($Queue->userqueue as $key => $user) {
				$user->user->name;
			}
		}
		
		$result = 'Success';
		
		return response()->json([
				'status' => $result,
				'result' => $Queues,
			]);
	}

	public function getQueueType(){
		$result = 'Success';
		return response()->json([
				'status' => $result,
				'result' => QueueType::all(),
			]);
	}

	public function AllCount(){
		$Queues = MainQueue::all();
		foreach ($Queues as $key => $Queue)
		{
			$Queue['current'] = $Queue->userqueue()->count();
		}
		
		return response()->json([
				'status' => 'Success',
				'result' => $Queues,
			]);
	}
}
