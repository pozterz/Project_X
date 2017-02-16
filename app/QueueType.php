<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueType extends Model
{
	 public function mainqueue(){
	  return $this->hasMany(MainQueue::class);
	 }
}
