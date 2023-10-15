<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        //Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');
        
        Passport::tokensCan([

            'admin' => 'show_all_players, list_all_percent, list_one_percent',
            'player' => 'dice_roll, list_rolls, delete_list, list_wins'
            
        ]);

        Passport::setDefaultScope([
            
            'player'
        ]);
    }
}
