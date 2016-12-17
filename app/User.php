<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','password', 'email','level', 'ip'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','role_id','have_role'
    ];


    public function user_info(){
        return $this->hasOne(UserInformation::class);
    }
    public function mainqueue(){
        return $this->hasMany(MainQueue::class);
    }

    public function userqueue()
    {
        return $this->hasMany(UserQueue::class);
    }

    public function isAdmin($user) {
        return ( $user->getUserRole()->name == 'Administrator' );
    }

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();
        // Check if the user is a root account
        if($this->have_role->name == 'Administrator') {
            return true;
        }

        if(is_array($roles)){
            foreach($roles as $need_role){
                if($this->checkIfUserHasRole($need_role)) {
                    return true;
                }
            }
        }else{
            return $this->checkIfUserHasRole($roles);
        }

        return false;
    }
    private function getUserRole(){
        return $this->role()->getResults();
    }
    private function getResults(){
        return $this->query->first();
    }
    private function checkIfUserHasRole($need_role){
        return (strtolower($need_role)==strtolower($this->have_role->name)) ? true : false;
    }
}
