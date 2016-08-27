<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQueue extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function mainqueue()
    {
        return $this->belongsTo(MainQueue::class);
    }
}
