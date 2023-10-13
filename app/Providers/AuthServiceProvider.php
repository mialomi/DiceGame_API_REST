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

            'admin' => 'show_all_players, win_percent, players_percent',
            'player' => 'game_play, list_plays, delete_list, wins_plays'
            
        ]);

        Passport::setDefaultScope([
            'player'
        ]);
    }
}
