<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainQueue extends Model
{
	protected $dates = [
        'workingtime',
		'open',
		'close',
		'created_at',
		'updated_at',
	];

     protected $fillable = [
        'name',
        'description',
        'counter',
        'workingtime',
        'workmin',
        'open',
        'close',
        'max',
        'created_at',
        'updated_at'
        ];

     protected $hidden = ['updated_at','pivot','created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Queuetype()
    {
        return $this->belongsTo(QueueType::class);
    }

    public function userqueue()
    {
        return $this->belongsToMany(UserQueue::class);
    }

}
