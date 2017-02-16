<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQueue extends Model
{
	protected $dates = [
		'time',
		'created_at',
		'updated_at',
	];

    protected $fillable = ['queue_id','user_id', 'captcha','time','isAccept'];

    protected $hidden = ['created_at','updated_at','pivot','captcha'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function mainqueue()
    {
        return $this->belongsToMany(MainQueue::class);
    }

    public function getQueue_captcha()
    {
        return $this->captcha;
    }

    public function getPivot()
    {
        return $this->pivot;
    }

}
