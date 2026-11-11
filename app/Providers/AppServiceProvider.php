<?php

namespace App\Providers;

use App\View\Composers\NotificationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The notification bell gets its data here instead of in every controller.
        View::composer('admin.assets.notif', NotificationComposer::class);
    }
}
