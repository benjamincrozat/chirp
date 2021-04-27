<?php

namespace Tests;

use Abraham\TwitterOAuth\TwitterOAuth;
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
            return new class($app['config']->get('services.twitter.client_id'), $app['config']->get('services.twitter.client_secret')) extends TwitterOAuth {
                public function get($path, array $parameters = [])
                {
                    $key = $path . '_' . md5(serialize($parameters));

                    if (cache()->has($key)) {
                        $this->response->setHttpCode(200);

                        return cache()->get($key);
                    }

                    $response = parent::get($path, $parameters);

                    // We cache the response only if we don't have errors.
                    if (empty($response->errors)) {
                        cache()->put($key, $response);
                    }

                    return $response;
                }
            };
        });
    }
}
