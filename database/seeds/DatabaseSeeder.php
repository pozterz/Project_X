<?php

use Illuminate\Database\Seeder;
use App\User;
use App\MainQueue;
use App\UserQueue;
use App\UserInformation;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call('UserTableSeeder');
        $this->call('MainQueueTableSeeder');
        $this->call('UserQueueTableSeeder');
        $this->call('UserInformationTableSeeder');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
class UserTableSeeder extends Seeder {
    public function run() {    
        User::truncate();
        factory(User::class,19)->create();
    }    
}
class MainQueueTableSeeder  extends Seeder {
    public function run() {
        MainQueue::truncate();
        factory(MainQueue::class,20)->create();
    }
}

class UserQueueTableSeeder  extends Seeder {
    public function run() {
        UserQueue::truncate();
        factory(UserQueue::class,20)->create();
    }
}
class UserInformationTableSeeder  extends Seeder {
    public function run() {
        UserInformation::truncate();
        factory(UserInformation::class,20)->create();
    }
}