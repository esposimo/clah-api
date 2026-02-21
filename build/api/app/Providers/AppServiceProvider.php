<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\RepositoryInterface;
use App\Infrastructure\Storage\EtcdRepository;
use Illuminate\Support\ServiceProvider;

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
