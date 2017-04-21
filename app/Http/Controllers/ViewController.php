<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MainQueue;
use App\UserQueue;
use App\Http\Requests;

class ViewController extends Controller
{
	public function index(){
		return view('main.index');
	}

	public function testMail(){
		$queue = MainQueue::find(1);
		$data = UserQueue::find(1);
		$this->sendMail($data,$queue);
		return view('layouts.mail',compact('queue','data'));
	}

	private function sendMail($data,$mainqueue){
    	//dd($mainqueue);

  }
}
