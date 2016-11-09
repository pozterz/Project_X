<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Validator;
use App\Http\Requests;
use App\User;
use App\MainQueue;
use App\UserQueue;
use App\UserInformation;
use Illuminate\Support\Facades\Input;
use Auth;
use Gate;

class AdminController extends Controller
{
	
    public function __construct(){
        if(Gate::denies('isAdmin',Auth::user())){
            abort(404);
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

    public function GetUser($id){
        $user = User::find($id);
        return view('admin.user',compact('user'));
    }

    public function NewUser(){
        return view('admin.newUser');
    }

    public function PostNewUser(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255|min:6|unique:users',
            'password' => 'required|confirmed|min:6',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'gender' => 'required',
            'card_id' => 'required|min:13|max:13|unique:user_informations',
            'address' => 'required|max:255|min:25',
            'tel' => 'required|min:10|max:10',
            'birthday' => 'required',
        ]);

        if($validator->fails()){
            return view('admin.newUser')->withErrors($validator);
        }

        $user = User::create([
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'email' => $request->get('email'),
            'level' => 'user',
            'ip' => $request->get('ip'),
        ]);
        
        $user_info = new UserInformation;
        $user_info->user_id = $user->id;
        $user_info->name = $request->get('name');
        $user_info->gender = $request->get('gender');
        $user_info->card_id = $request->get('card_id');
        $user_info->address = $request->get('address');
        $user_info->tel = $request->get('tel');
        $user_info->birthday = $this->ConvertDate($request->get('birthday'),'00:00');
        $user_info->save();

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
        $info = UserInformation::where('user_id',$id)->firstOrFail();
        $info->name = $req->get('name');
        $info->gender = $req->get('gender');
        $info->card_id = $req->get('card_id');
        $info->address = $req->get('address');
        $info->tel = $req->get('tel');
        $info->birthday = $this->ConvertDate($req->get('birthday'),'00:00');
        $user->save();
        $info->save();

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
        $mainqueues = MainQueue::where('opentime','>',Carbon::now()->toDateTimeString())->orderBy('end','asc')->paginate(20);
        return view('admin.Activity-panel',compact('mainqueues'));
    }

    public function AllActivities(){
        $mainqueues = MainQueue::where('end','<',Carbon::now()->toDateTimeString())->where('opentime','<',Carbon::now()->toDateTimeString())->orderBy('end','desc')->paginate(10);
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
        $Queue->user_id = Auth::user()->id;
        $Queue->status = 'ready';

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
