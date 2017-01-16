<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserInformation;
use Validator;
use Auth;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/index2';
    protected $redirectAfterLogout = '/index2';
    protected $username = 'username';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
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
    }
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        
        $user = $this->create($request->all());

        $user_info = new UserInformation;
        $user_info->user_id = $user->id;
        $user_info->name = $request->get('name');
        $user_info->gender = $request->get('gender');
        $user_info->card_id = $request->get('card_id');
        $user_info->address = $request->get('address');
        $user_info->tel = $request->get('tel');
        $user_info->birthday = $this->ConvertDate($request->get('birthday'),'00:00');
        $user_info->save();

        Auth::guard($this->getGuard())->login($user);
        $request->session()->flash('success', 'Register Completed!');
        return redirect($this->redirectPath());
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'ip' => $data['ip'],
        ]);
    }
    //custom login error message
    protected function getFailedLoginMessage(){
        return "Username หรือ Password ผิดพลาด";
    }

    public function authenticated($request, $user)
    {
        $request->session()->flash('success', 'Logged in as ' . $user->username);
        $request->session()->reflash();
        return redirect($this->redirectPath());
    }

    public function logout()
    {
        Auth::guard($this->getGuard())->logout();
        Session::flash('success', 'You have been successfully logged out!');
        return redirect($this->redirectAfterLogout);
    }
    public function ConvertDate($date,$time){
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
