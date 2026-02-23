<?php

declare(strict_types=1);

namespace src\app\Providers;

use Illuminate\Support\ServiceProvider;
use src\app\Domain\RepositoryInterface;
use src\app\Infrastructure\Storage\EtcdRepository;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, EtcdRepository::class);
    }

    public function boot(): void
    {
    }
}
