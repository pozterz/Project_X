<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\UserQueue;
use Mail;

class emailUser extends Command
{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'email:user';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Test';

		/**
		 * Create a new command instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
				parent::__construct();
		}

		/**
		 * Execute the console command.
		 *
		 * @return mixed
		 */
		public function handle()
		{
				$users = UserQueue::where('isnotify',0)->get();
				foreach ($users as $key => $user) {
						if(Carbon::now()->addMinutes(20) >= $user->time){
								$mainqueue = $user->mainqueue;
								$this->sendMail($user,$mainqueue);
								$user->isnotify = 1;
								$user->save();
						}
				}
		}

		private function sendMail($data,$mainqueue){
			Mail::queue('layouts.notify', ['data' => $data,'queue' => $mainqueue[0]], function ($m) use ($data)  {
					$m->from('pozterz2@gmail.com', '[Notify]Queue System Notify');
					$m->to($data->user->email);
			});

			return true;
		}
}
