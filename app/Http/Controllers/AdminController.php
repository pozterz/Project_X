<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Auth;
use File;
use Gate;
use Validator;
use App\User;
use App\MainQueue;
use App\UserQueue;
use App\QueueType;
use App\UserInformation;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AdminController extends Controller
{
	
		public function __construct(){
				if(Gate::denies('isModerator',Auth::user())){
					if(Gate::denies('isAdmin',Auth::user())){
						abort(403);
					}
				}
		}

		public function Index(){
			return view('admin.index');
		}


		//----------------------------------
		//-         User Section
		//----------------------------------

		
		public function ManageUser(){
				$users = User::paginate(20);
				return view('admin.User-panel',compact('users'));   
		}

		/*public function GetUser($id){
				$user = User::find($id);
				return view('admin.user',compact('user'));
		}
*/
		public function NewUser(){
				return view('admin.newUser');
		}

		public function PostNewUser(Request $request){
				$validator = Validator::make($request->all(), [
						'username' => 'required|max:255|min:6|unique:users',
						'password' => 'required|confirmed|min:6',
						'name' => 'required|max:255',
						'email' => 'required|email|max:255|unique:users',
						'phone' => 'required|min:10|max:10',
				]);

				if($validator->fails()){
						return view('admin.newUser')->withErrors($validator);
				}

				$user = User::create([
						'username' => $data['username'],
						'password' => bcrypt($data['password']),
						'name'  => $data['name'],
						'email' => $data['email'],
						'phone' => $data['phone'],
						'role_id' => 2,
						'ip' => $data['ip'],
				]);
				
				$request->session()->flash('success', 'Add User Completed!');
				
				return Redirect('/admin');
		}

		public function EditUser($id){
				$user = User::find($id);
				return view('admin.edituser',compact('user'));
		}

		public function PostEdit(Request $req,$id){
				$user = User::find($id);
				$user->username = $req->get('username');
				$user->password = bcrypt($req->get('password'));
				$user->email = $req->get('email');
				$user->name = $req->get('name');
				$user->tel = $req->get('phone');
				$user->role_id = $req->get('role_id');
				$user->save();

				$req->session()->flash('success', 'Edit Completed!');
				return Redirect('/admin');
		}


		public function ViewUserQueue($id){
				$user = User::find($id);
				$userqueues = UserQueue::where('user_id',$id)->paginate(20);
				return view('admin.userqueue',compact('user','userqueues'));

		}

	 

		//----------------------------------
		//-         Activities Section
		//----------------------------------

		
		public function Activities(){
				//$mainqueues = MainQueue::orderBy('created_at','desc')->paginate(20);
				$mainqueues = MainQueue::where('workingtime','>',Carbon::now()->toDateTimeString())->orderBy('close','asc')->paginate(20);
				return view('admin.Activity-panel',compact('mainqueues'));
		}

		public function AllActivities(){
				$mainqueues = MainQueue::where('close','<',Carbon::now()->toDateTimeString())->where('workingtime','<',Carbon::now()->toDateTimeString())->orderBy('close','desc')->paginate(10);
				return view('admin.Activity-panel',compact('mainqueues'));
		}

		public function Activity($id){
				$mainqueue = MainQueue::find($id);
				return view('admin.activity',compact('mainqueue'));
		}

		public function NewActivity(){
				return view('admin.newActivity');
		}

		public function PostNewActivity(Request $request){
				$validator = Validator::make($request->all(),[
						'name' => 'required|string|max:150',
						'description' => 'required|string|max:500',
						'counter' => 'required|string|max:100',
						'workingtime' => 'required',
						'workmin' => 'required|integer',
						'open' => 'required',
						'close' => 'required',
						'max' => 'required|integer',
				]);

				if($validator->fails()){
						return view('admin.newActivity')->withErrors($validator);
				}
				$Queue = new MainQueue;
				$Queue->name = $request->get('name');
				$Queue->description = $request->get('description');
				$Queue->counter = $request->get('counter');
				$Queue->workingtime = $this->ConvertDate($request->get('workingtime'),$request->get('working_time'));
				$Queue->workmin = $request->get('workmin');
				$Queue->open = $this->ConvertDate($request->get('open'),$request->get('open_time'));
				$Queue->close = $this->ConvertDate($request->get('close'),$request->get('close_time'));
				$Queue->current = 0;
				$Queue->max = $request->get('max');
				$Queue->user_id = Auth::user()->id;
				$Queue->save();
				
				$request->session()->flash('success', 'Added new activity!');
				return Redirect('/admin');
		}

		public function DeleteActivity($id){
				$Queue = MainQueue::find($id);
				foreach($Queue->userqueue as $userqueue){
						$userqueue->delete();
				}
				$Queue->delete();
				return Redirect('/admin/activities');
		}

		public function QueueUserList($id){
				$mainqueue = MainQueue::find($id)->userqueue()->paginate(20);
				return view('admin.userlist',compact('mainqueue','id'));
		}

		public function editActivity($id){
				$Activity = MainQueue::find($id);
				return view('admin.editActivity',compact('Activity'));
		}

		 public function UpdateActivity(Request $request){
				
				$queue = MainQueue::find($request->get('id'));
				$queue->queue_name = $request->get('queue_name');
				$queue->counter = $request->get('counter');
				$queue->service_time = $request->get('service_time');
				$queue->max_count = $request->get('max_count');
				$queue->opentime = $this->ConvertDate($request->get('opentime'),$request->get('opentime_time'));
				$queue->start = $this->ConvertDate($request->get('start'),$request->get('start_time'));
				$queue->end = $this->ConvertDate($request->get('end'),$request->get('end_time'));
				$queue->save();

				$request->session()->flash('success', 'Edit Completed!');
				return Redirect('/admin');
		 }

		//----------------------------------
		//-         Queue Check Section
		//----------------------------------

		public function AcceptUser($id,$userqueue_id){
				UserQueue::find($userqueue_id)->update(['isAccept' => 'yes']);
				return Redirect('/admin/userList/'.$id);
		}
		public function removeAccepted($id,$userqueue_id){
				UserQueue::find($userqueue_id)->update(['isAccept' => 'no']);
				return Redirect('/admin/userList/'.$id);
		}

		//------------------------------------
		//-         New APIs
		//------------------------------------

		public function getUsers()
		{
				if($this->isAdmin()){
					$Users = User::all();
				}
				else if(Auth::user()->hasRole('moderator')){
					$Users = User::where('role_id',2)->get();
				}
				return response()->json([
						'status' => 'Success',
						'result' => $Users,
						]);
		}

		public function getUser($id)
		{

				$result = 'Failed';

				try
				{
						$User = User::find($id);
						$User['Phone'] = $User->getPhone();
						if(!$this->isAdmin()){
							if($this->isUserAreAdmin($User)){
								$User = null;
							}
						}
						$result = 'Success';
				}
				catch(ModelNotFoundException $ex)
				{
						return response()->json([
								'status' => $result,
								'result' => null,
						]);
				}



				return response()->json([
						'status' => $result,
						'result' => $User,
						]);
		}

		public function getUserReserved($id)
		{
				$result = 'Success';
				try
				{
						if($this->isAdmin()){
							$UserQueue = UserQueue::where('user_id',$id)
								->where('isAccept','no')->where('time','>',Carbon::now()->toDateTimeString())
								->get();
						}
						else{
							$UserQueue = UserQueue::where('user_id',$id)->where('isAccept','no')
								->where('time','>',Carbon::now()->toDateTimeString())
								->get();
						}
						foreach ($UserQueue as $key => $Queue) {
								$Queue->mainqueue;
								$Queue['captcha_key'] = $Queue->getQueue_captcha();
						}
						$result = 'Success';
				}
				catch(ModelNotFoundException $ex)
				{
						return response()->json([
								'status' => $result,
								'result' => null,
						]);
				}
				return response()->json([
						'status' => $result,
						'result' => $UserQueue,
						]);
		}

		public function getUserHistory($id)
		{
				$result = 'Success';
				try
				{
						$UserQueue = UserQueue::where('user_id',$id)
							->where('time','<',Carbon::now()->toDateTimeString())->get();

						foreach ($UserQueue as $key => $Queue) {
								$Queue->mainqueue;
								$Queue['captcha_key'] = $Queue->getQueue_captcha();
						}
						$result = 'Success';
				}
				catch(ModelNotFoundException $ex)
				{
						return response()->json([
								'status' => $result,
								'result' => null,
						]);
				}
				return response()->json([
						'status' => $result,
						'result' => $UserQueue,
						]);
		}

		public function getQueues(){

				if($this->isAdmin()){
					$Queues = MainQueue::all();
				}
				else{
					$Queues = MainQueue::where('user_id',Auth::user()->id)->get();
				}

				foreach ($Queues as $key => $Queue)
				{
					$Queue->QueueType;
					$Queue['current'] = $Queue->userqueue()->count();
				}

				$result = 'Success';
				return response()->json([
								'status' => $result,
								'result' => $Queues,
						]);
		}

		public function getUserInQueue($id){
			 $result = 'Success';
				try
				{
						$Queue = MainQueue::find($id);
						$userList = $Queue->userqueue;
						foreach ($userList as $key => $user) {
							 $user->user;
							 $user['captcha_key'] = $user->getQueue_captcha();
							 $user->user['phoneNo'] = $user->user->getPhone();
						}
						$result = 'Success';
				}
				catch(ModelNotFoundException $ex)
				{
						return response()->json([
								'status' => $result,
								'result' => null,
						]);
				}
				return response()->json([
						'status' => $result,
						'result' => $userList,
						]); 
		}

		public function addNewType(Request $request){
			$result = 'Success';
			$validator = Validator::make($request->all(),[
						'name' => 'required|string|max:255',
						'requirement' => 'string',
						'document' => 'string',
						'description' => 'string'
				]);

			if($validator->fails()){
						$result = 'Failed';
						return response()->json([
								'status' => $result,
								'result' => $validator->errors(),
						]);
				}

			$newType = new QueueType;
			$newType->name = $request->get('name');
			$newType->requirement = $request->get('requirement');
			$newType->document = $request->get('document');
			$newType->description = $request->get('description');
			$newType->save();

			$request->session()->flash('success', 'Added new queue type!');

				return response()->json([
						'status' => $result,
						'result' => $newType,
				]);

		}

		public function UpdateType(Request $request){
			$result = 'Success';
			$validator = Validator::make($request->all(),[
						'name' => 'required|string|max:255',
						'requirement' => 'string',
						'document' => 'string',
						'description' => 'string'
				]);

			if($validator->fails()){
						$result = 'Failed';
						return response()->json([
								'status' => $result,
								'result' => $validator->errors(),
						]);
				}
			$type = QueueType::find($request->id);
			$type->name = $request->get('name');
			$type->requirement = $request->get('requirement');
			$type->document = $request->get('document');
			$type->description = $request->get('description');
			$type->save();

			$request->session()->flash('success', 'Save queue type success!');

				return response()->json([
						'status' => $result,
						'result' => $type,
				]);
		}

		public function DeleteType($id){
			try {
				$type = QueueType::find($id);
				$type->delete();

				return response()->json([
						'status' => 'Success',
						'result' => null,
				]);
			}
			catch(ModelNotFoundException $ex){
				return response()->json([
						'status' => 'Failed',
						'result' => null,
				]);
			}
		}

		public function addNewQueue(Request $request){
				$result = 'Success';

				$validator = Validator::make($request->all(),[
						'name' => 'required|string|max:150',
						'service_start' => 'required',
						'service_end' => 'required',
						'max_minutes' => 'required|integer',
						'open' => 'required',
						'close' => 'required',
						'max' => 'required|integer',
				]);

				if($validator->fails()){
						$result = 'Failed';
						return response()->json([
								'status' => $result,
								'result' => $validator->errors(),
						]);
				}

				$Queue = new MainQueue;
				$Queue->name = $request->get('name');
				$Queue->queuetype_id = $request->get('queuetype_id');
				$Queue->counter = Auth::user()->counter_id;
				$Queue->service_start = $this->ConvertDate($request->get('service_start'),$request->get('service_start_time'));
				$Queue->service_end = $this->ConvertDate($request->get('service_end'),$request->get('service_end_time'));
				$Queue->max_minutes = $request->get('max_minutes');
				$Queue->open = $this->ConvertDate($request->get('open'),$request->get('open_time'));
				$Queue->close = $this->ConvertDate($request->get('close'),$request->get('close_time'));
				$Queue->max = $request->get('max');
				$Queue->user_id = Auth::user()->id;
				$Queue->save();

				$request->session()->flash('success', 'Added new queue!');

				return response()->json([
						'status' => $result,
						'result' => $Queue,
				]);
		}

		public function deleteUser($id){
				$result = 'Failed';

				try
				{
						$user = User::find($id);
						$user->delete();
				}
				catch(ModelNotFoundException $ex)
				{
						return response()->json([
								'status' => $result,
								'result' => null,
						]);
				}

				return response()->json([
						'status' => $result,
						'result' => 'Success',
						]);
		}

		public function deleteQueue($id)
		{
		 $result = 'Failed';

				try
				{
						$result = 'Success';
						$queue = MainQueue::find($id);
						$queue->delete();
				}
				catch(ModelNotFoundException $ex)
				{
						return response()->json([
								'status' => $result,
								'result' => null,
						]);
				}

				return response()->json([
						'status' => $result,
						'result' => 'Success',
						]);
		}

		 public function DeleteUserQueue($id){

				$result = 'Failed';

				try
				{
						$user = UserQueue::find($id);
						$user->mainqueue->first()->userqueue()->detach($user->id);
						$user->delete();
				}
				catch(ModelNotFoundException $ex)
				{
						return response()->json([
								'status' => $result,
								'result' => null,
						]);
				}

				return response()->json([
						'status' => $result,
						'result' => 'Success',
						]);
		}

		public function getRunningQueues()
		{
				if($this->isAdmin()){
					$Queues = MainQueue::where('service_start','<=',Carbon::now()->toDateTimeString())
						->where('service_end','>',Carbon::now()->toDateTimeString())
						->where('close','<',Carbon::now()->toDateTimeString())
						->orderBy('service_start','desc')->get();
				}
				else{
					$Queues = MainQueue::where('service_start','<=',Carbon::now()->toDateTimeString())
							->where('service_end','>',Carbon::now()->toDateTimeString())
							->where('close','<',Carbon::now()->toDateTimeString())
							->where('user_id',Auth::user()->id)
							->orderBy('service_start','desc')->get();
				}

				foreach ($Queues as $key => $Queue) {
						$Queue->QueueType;
						$Queue['current'] = $Queue->userqueue()->count();
						foreach ($Queue->userqueue as $key => $user) {
								$user['captcha_key'] = $user->getQueue_captcha();
								$user->user['phoneNo'] = $user->user->getPhone();
						}
				}
				
				$result = 'Success';
				
				return response()->json([
								'status' => $result,
								'result' => $Queues,
						]);
		}

		public function UserQueueDetail($queue_id,$userqueue_id)
		{
			$result = 'Failed';
			$files = File::allFiles('files');
				$user = UserQueue::find($userqueue_id);
				if($user){
					$filename = $user->user->username.'_'.$queue_id;
					$fileArr = [];
					foreach ($files as $file)
					{
				    if(str_contains((string)$file, $filename)){
				    	$file = [
				    		'filename' => $file->getFilename(),
				    		'extension' => $file->getExtension()
				    	];
				    	array_push($fileArr,$file);
				    }
					}
					return view('admin.userQueueDetail',compact('queue_id','userqueue_id','fileArr'));
				}else{
					return response()->json([
					'status' => $result,
					'result' => null,
					]);
				}
		}

		public function getUserQueueDetail($queue_id,$userqueue_id)
		{
				$result = 'Failed';
				try {
						$queue = MainQueue::find($queue_id);
						$queue->QueueType;
						$userqueue = UserQueue::find($userqueue_id);
						if($userqueue){
							$userqueue['captcha_key'] = $userqueue->getQueue_captcha();
							$userqueue->user['phoneNo'] = $userqueue->user->getPhone();
						}
						else{
							return response()->json([
							'status' => $result,
							'result' => null,
							]);
						}

						$result = 'Success';

						$res = array(
							'queue' => $queue,
							'userqueue' => $userqueue
							);

				} catch (ModelNotFoundException $ex) {
						 return response()->json([
						'status' => $result,
						'result' => null,
						]);
				}

		 return response()->json([
				'status' => $result,
				'result' => $res,
				]);
		}

		public function AcceptQueue($queue_id,$userqueue_id){
			$result = 'Failed';
				try {
						$queue = MainQueue::find($queue_id);
						$queue->QueueType;
						$userqueue = UserQueue::find($userqueue_id);
						if($userqueue){
							$userqueue->update(['isAccept'=>'yes']);
						}
						else{
							return response()->json([
							'status' => $result,
							'result' => null,
							]);
						}

						$result = 'Success';

				} catch (ModelNotFoundException $ex) {
						 return response()->json([
						'status' => $result,
						'result' => null,
						]);
				}

		 return response()->json([
				'status' => $result,
				'result' => 'Success',
				]);
		}

		public function CancelQueue($queue_id,$userqueue_id){
				$result = 'Failed';
				try {
						$queue = MainQueue::find($queue_id);
						$queue->QueueType;
						$userqueue = UserQueue::find($userqueue_id);
						if($userqueue){
							$userqueue->update(['isAccept'=>'no']);
						}
						else{
							return response()->json([
							'status' => $result,
							'result' => null,
							]);
						}

						$result = 'Success';

				} catch (ModelNotFoundException $ex) {
						 return response()->json([
						'status' => $result,
						'result' => null,
						]);
				}

		 return response()->json([
				'status' => $result,
				'result' => 'Success',
				]);
		}

		public function addMod($id){
			if($this->isAdmin()){
				$user = User::find($id);
				$user->role_id = 3;
				$user->save();
			}
			return response()->json([
				'result' => 'Success',
				]);
		}

		public function removeMod($id){
			if($this->isAdmin()){
				$user = User::find($id);
				$user->role_id = 2;
				$user->save();
			}
			return response()->json([
				'result' => 'Success',
				]);
		}

		public function getQueueTypes(){
			$types = QueueType::all();
			return response()->json([
				'status' => 'Success',
				'result' => $types,
				]);
		}

		//----------------------------------
		//-         Function Section
		//----------------------------------

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

		private function isAdmin(){
			return Auth::user()->hasRole('administrator');
		}

		private function isUserAreAdmin($user){
			return $user->hasRole('administrator');
		}

}
