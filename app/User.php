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
        'username','password','name', 'email','level', 'ip'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function user_info(){
        return $this->hasOne(UserInformation::class);
    }
    public function mainqueue(){
        return $this->hasMany(MainQueue::class);
    }

    public function userqueue()
    {
        return $this->hasMany(UserQueue::class)->orderBy('queue_time');
    }
    
}
