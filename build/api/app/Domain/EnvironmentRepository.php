<?php

declare(strict_types=1);

namespace App\Domain;

final class EnvironmentRepository
{
    private const BASE_PATH = '/environments';

    public function __construct(private readonly RepositoryInterface $repository)
    {
    }

    public function get(string $id): ?Environment
    {
        $payload = $this->repository->get($this->resourcePath($id));

        if (! is_string($payload)) {
            return null;
        }

        $attributes = json_decode($payload, true);

        return is_array($attributes) ? new Environment($attributes) : null;
    }

    public function save(Environment $environment): bool
    {
        return $this->repository->put(
            $this->resourcePath($environment->getId()),
            json_encode($environment->toArray(), JSON_THROW_ON_ERROR)
        );
    }

    public function del(Environment $environment): bool
    {
        return $this->repository->del($this->resourcePath($environment->getId()));
    }

    public function getEnvironmentByName(string $name): ?Environment
    {
        foreach ($this->list() as $environment) {
            if ($environment->getName() === $name) {
                return $environment;
            }
        }

        return null;
    }

    /**
     * @return array<int, Environment>
     */
    public function list(): array
    {
        $keys = $this->repository->list(self::BASE_PATH);

        if ($keys === null) {
            return [];
        }

        $environments = [];

        foreach ($keys as $key) {
            if (! is_string($key)) {
                continue;
            }

            $id = basename($key);
            $environment = $this->get($id);

            if ($environment !== null) {
                $environments[] = $environment;
            }
        }

        return $environments;
    }

    private function resourcePath(string $id): string
    {
        return self::BASE_PATH.'/'.trim($id, '/');
    }
}
