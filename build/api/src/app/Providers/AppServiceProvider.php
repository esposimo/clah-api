<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Environment\EnvironmentService;
use App\Services\Etcd\EtcdClient;
use App\Services\Network\NetworkService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Register project service bindings.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(EtcdClient::class, function (Application $app): EtcdClient {
            $endpoint = (string) $app['config']->get('services.etcd.endpoint', 'http://clah-kv-store:2379');

            return new EtcdClient($endpoint);
        });

        $this->app->singleton(EnvironmentService::class, function (Application $app): EnvironmentService {
            return new EnvironmentService($app->make(EtcdClient::class));
        });

        $this->app->singleton(NetworkService::class, function (Application $app): NetworkService {
            return new NetworkService($app->make(EtcdClient::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
