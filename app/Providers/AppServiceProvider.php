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

                Activity::saving(function (Activity $activity) {
                    $activity->ip = \Request::ip();
                    if(backpack_user()){
                        $activity->causer_id = backpack_user()->id;
                    }
                });

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
