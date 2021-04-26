<?php

namespace Tests;

use App\Twitter\TwitterOAuthWithCache;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        app()->bind('twitter', function (Application $app) {
            return new TwitterOAuthWithCache(
                $app['config']->get('services.twitter.client_id'),
                $app['config']->get('services.twitter.client_secret'),
            );
        });
    }
}
