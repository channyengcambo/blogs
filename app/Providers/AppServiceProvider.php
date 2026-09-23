<?php

namespace App\Providers;

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
        View::composer('layouts.app', function ($view) {
            $route = request()->route()?->getName() ?? '';

            $active = match (true) {
                str_starts_with($route, 'admin.categories.') => 'categories',
                str_starts_with($route, 'admin.posts.') => 'posts',
                str_starts_with($route, 'admin.tags.') => 'tags',
                str_starts_with($route, 'admin.dashboard') => 'dashboard',
                str_starts_with($route, 'profile.') => 'profile',
                default => 'dashboard',
            };

            $view->with('activeSidebar', $active);
        });
    }
}
