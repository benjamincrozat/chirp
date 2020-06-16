<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind('twitter', function (Application $app) {
            return new TwitterOAuth(
                $app['config']->get('services.twitter.client_id'),
                $app['config']->get('services.twitter.client_secret')
            );
        });
    }
}
