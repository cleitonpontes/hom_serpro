<?php

namespace App\Providers;

use App\Extensions\ContaUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // add custom guard provider
        Auth::provider('conta', function ($app, array $config) {
          //return new ContaUserProvider($app['hash'], $app->make('App\Models\BackpackUser'));
          return new ContaUserProvider($app['hash'], 'App\Models\BackpackUser');
         //$this->app['hash'], $config['model']
          //return new ContaUserProvider(null,$app->make('App\Models\BackpackUser'));
        });
    }
}
