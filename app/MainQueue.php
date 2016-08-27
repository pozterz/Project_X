<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainQueue extends Model
{
	protected $dates = [
		'start',
		'end',
		'created_at',
		'updated_at',
	];
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function userqueue()
    {
        return $this->hasMany(UserQueue::class);
    }

}
