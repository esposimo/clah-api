<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\RepositoryInterface;
use App\Domain\EnvironmentRepository;
use App\Infrastructure\Storage\EtcdRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            RepositoryInterface::class,
            EnvironmentRepository::class
        );
        $this->app->bind(RepositoryInterface::class, EtcdRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
