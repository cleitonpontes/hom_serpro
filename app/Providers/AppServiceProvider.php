<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if (!app()->runningInConsole()) {
            if(backpack_user()){
                Activity::saving(function (Activity $activity) {
                    $activity->ip = \Request::ip();
                    $activity->causer_id = backpack_user()->id;
                });
            }
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
