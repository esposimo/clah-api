<?php

declare(strict_types=1);

namespace App\Domain;

interface RepositoryInterface
{
    public function get(string $path): mixed;

    public function put(string $path, string $value): bool;

    public function del(string $path): bool;

    public function list(string $path): ?array;
}
