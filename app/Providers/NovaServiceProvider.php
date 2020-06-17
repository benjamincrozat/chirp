<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Register the Nova routes.
     */
    protected function routes() : void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate() : void
    {
        Gate::define('viewNova', fn ($user) => 71785320 === $u->id);
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     */
    protected function cards() : array
    {
        return [];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     */
    protected function dashboards() : array
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     */
    public function tools() : array
    {
        return [];
    }
}
