<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','id','card_id', 'tel','address','birthday','gender'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}