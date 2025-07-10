<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
       /**
     * Where to redirect users after login / when they try to hit a guest route.
     */
    public const HOME = 'admin.index'; // Use the route name instead of path

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
          Paginator::useBootstrap();
        Gate::define('assign tickets', function ($user) {
    return $user->hasRole('admin') || $user->hasRole('manager');
});
       

    }
}
