<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQueue extends Model
{
	protected $dates = [
		'queue_time',
		'created_at',
		'updated_at',
	];

    protected $fillable = ['queue_id', 'user_id', 'queue_captcha','queue_time','isAccept'];

    protected $hidden = ['queue_captcha','created_at','updated_at','pivot'];

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
        return $this->queue_captcha;
    }

    public function getPivot()
    {
        return $this->pivot;
    }

}
