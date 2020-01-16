<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Setting;
use App\Models\MobileNotification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \View::composer(['layouts.backend'], function($view) {
            
            $roles = Role::where('permission_id', auth()->user()->permission_id)->pluck('page')->toArray();
            $count = MobileNotification::whereIn('user_type',  ['1','2','7', '11', '13', '33', '34', '35', '36', '37'])->where('read_at', null)->count();

            $view->with(['roles' => $roles, 'notificationsCount' => $count]);
        });

        \View::composer(['layouts.providers'], function($view) {
            
            $count = MobileNotification::where('provider_id', auth('providers')->id())->whereIn('user_type',  ['4','6', '8', '10', '12', '16'])->where('read_at', null)->count();

            $view->with(['notificationsCount' => $count]);
        });

        \View::composer(['layouts.front'], function($view) {
            
            $setting = Setting::first();
            $count = MobileNotification::where('client_id', auth('clients')->id())->whereIn('user_type',  ['3','5', '9','14','15'])->where('read_at', null)->count();

            $view->with(['setting' => $setting, 'notificationsCount' => $count]);
        });
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
