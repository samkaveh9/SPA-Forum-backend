<?php

namespace App\Providers;

use App\Answer;
use App\Thread;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function($user ,$ability){
            return $user->hasRole('super admin') ? true : null;
        });

        Gate::define('user-thread', function(User $user, Thread $thread){
            return $user->id == $thread->user_id;
        }); 

        Gate::define('user-answer', function(User $user, Answer $answer){
            return $user->id == $answer->user_id;
        }); 
    }
}
