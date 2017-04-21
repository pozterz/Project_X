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
        'username','password','email','counter_id','ip','role_id','name','phone'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','phone'
    ];


    public function mainqueue(){
        return $this->hasMany(MainQueue::class);
    }

    public function userqueue()
    {
        return $this->hasMany(UserQueue::class);
    }

    public function isAdmin($user) {
        return ( $user->getUserRole()->name == 'administrator' );
    }

     public function isModerator($user) {
        return ( $user->getUserRole()->name == 'moderator' );
    }

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();
        // Check if the user is a root account
        if($this->have_role->name == 'administrator') {
            return true;
        }


        if(is_array($roles)){
        dd($this->have_role->name);
            
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
    public function getUserRole(){
        return $this->role()->getResults();
    }
    private function getResults(){
        return $this->query->first();
    }
    private function checkIfUserHasRole($need_role){
        return (strtolower($need_role)==strtolower($this->have_role->name)) ? true : false;
    }

    public function getPhone()
    {
        return $this->phone;
    }
}
