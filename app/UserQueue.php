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

    protected $fillable = array('queue_id', 'user_id', 'queue_captcha','queue_time','isAccept');

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function mainqueue()
    {
        return $this->belongsToMany(MainQueue::class);
    }
}
