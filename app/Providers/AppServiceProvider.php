<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Notifications\DatabaseNotification;
use App\Policies\NotificationPolicy;

class AppServiceProvider extends ServiceProvider
{
       /**
     * Where to redirect users after login / when they try to hit a guest route.
     */
    public const HOME = 'admin.index'; // Use the route name instead of path

    /**
     * Bootstrap any application services.
     */

      protected $policies = [
        DatabaseNotification::class => NotificationPolicy::class,
        // Add other policies here
    ];

public function boot(): void
{
    //
    Paginator::useBootstrap();

    $this->registerPolicies();

    Gate::define('assign tickets', function ($user) {
        return $user->hasRole('admin') || $user->hasRole('manager');
    });
}

/**
 * Register the application's policies.
 *
 * @return void
 */
protected function registerPolicies()
{
    foreach ($this->policies as $key => $value) {
        Gate::policy($key, $value);
    }
}
}
