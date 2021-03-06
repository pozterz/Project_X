<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('isAdmin',function($user){
            if($user->hasRole('administrator')){
                return true;
            }
            return false;
        });

        $gate->define('isModerator',function($user){
            if($user->hasRole('moderator')){
                return true;
            }
            return false;
        });

        $gate->define('isUser',function($user){
            if($user->hasRole('user')){
                return true;
            }
            return false;
        });
        
    }
}
