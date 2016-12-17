<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainQueue extends Model
{
	protected $dates = [
        'opentime',
		'start',
		'end',
		'created_at',
		'updated_at',
	];

     protected $fillable = [
        'queue_name',
        'counter',
        'opentime',
        'service_time',
        'start',
        'end',
        'status',
        'max_count',
        'created_at',
        'updated_at'];

     protected $hidden = ['updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userqueue()
    {
        return $this->belongsToMany(UserQueue::class);
    }

}
