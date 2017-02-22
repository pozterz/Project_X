<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Auth;
use Gate;
use Validator;
use App\User;
use App\MainQueue;
use App\UserQueue;
use App\UserInformation;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AdminController extends Controller
{
	
    public function __construct(){
        if(Gate::denies('isAdmin',Auth::user())){
            abort(403);
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

    public function DeleteUser($id){
        $user = User::find($id);
        $user->delete();
        return Redirect('/admin/users');
    }


    public function DeleteUserQueue($id){
        $user = UserQueue::find($id);
        $user->mainqueue->first()->userqueue()->detach($user->id);
        $user->mainqueue->first()->current_count -= 1;
        $user->mainqueue->first()->save();
        $user->delete();
        return Back();
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
        $Users = User::all();
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
            $UserQueue = UserQueue::where('user_id',$id)->where('isAccept','no')->where('time','>',Carbon::now()->toDateTimeString())->get();
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
            $UserQueue = UserQueue::where('user_id',$id)->where('time','<',Carbon::now()->toDateTimeString())->get();
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
        
        $Queues = MainQueue::all();
        $result = 'Success';
        return response()->json([
                'status' => $result,
                'result' => $Queues,
            ]);
    }

    public function addNewQueue(Request $request){
        $result = 'Success';

         $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:150',
            'counter' => 'required|string|max:100',
            'workingtime' => 'required',
            'workmin' => 'required|integer',
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
        $Queue->counter = $request->get('counter');
        $Queue->workingtime = $this->ConvertDate($request->get('workingtime'),$request->get('workingtime_time'));
        $Queue->workmin = $request->get('workmin');
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

}
