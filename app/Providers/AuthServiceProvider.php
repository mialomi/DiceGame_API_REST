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

            'admin' => 'list_all_players, list_ranking, list_winner, list_loser',
            'player' => 'dice_roll, list_rolls, delete_list'
            
        ]);

        Passport::setDefaultScope([
            
            'player'
        ]);
    }
}
