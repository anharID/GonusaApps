<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\App;

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
        View::composer('dashboard.layouts.sidebar', function ($view) {
            if (auth()->check()) {
                if (auth()->user()->id === 1) {
                    $sidebarApps = App::where('data_status', 1)
                        ->orderBy('app_group')
                        ->orderBy('app_name')
                        ->get()
                        ->groupBy('app_group');
                } else {
                    $sidebarApps = auth()->user()->apps()
                        ->where('apps.data_status', 1)
                        ->wherePivot('data_status', 1)
                        ->orderBy('app_group')
                        ->orderBy('app_name')
                        ->get()
                        ->groupBy('app_group');
                }
                $view->with('sidebarApps', $sidebarApps);
            }
        });
    }
}
