<?php

namespace Sfneal\ViewModels\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Lunaweb\RedisMock\Providers\RedisMockServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Sfneal\Helpers\Redis\Providers\RedisHelpersServiceProvider;
use Sfneal\Helpers\Redis\RedisCache;
use Sfneal\ViewModels\Tests\Providers\TestingServiceProvider;
use Spatie\ViewModels\Providers\ViewModelsServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class TestCase extends OrchestraTestCase
{
    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', true);
        $app['config']->set('database.redis.client', 'mock');
        $app['config']->set('cache.default', 'redis');
        $app['config']->set('cache.prefix', 'redis-helpers');
    }

    /**
     * Register package service providers.
     *
     * @param Application $app
     * @return array|string
     */
    protected function getPackageProviders($app)
    {
        return [
            RedisHelpersServiceProvider::class,
            RedisMockServiceProvider::class,
            ViewModelsServiceProvider::class,
            TestingServiceProvider::class
        ];
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__.'/resources/views');
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        RedisCache::flush();
        parent::tearDown();
    }

    protected function createRequest(array $headers = []): Request
    {
        $request = Request::create('/', 'GET', [], [], [], [], []);

        foreach ($headers as $header => $value) {
            $request->headers->set($header, $value);
        }

        return $request;
    }

    protected function getResponseBody(Response $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
