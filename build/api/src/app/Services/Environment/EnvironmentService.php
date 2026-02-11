<?php

declare(strict_types=1);

namespace App\Services\Environment;

use App\Services\Etcd\EtcdClient;
use DateTimeImmutable;

/**
 * Handles environment persistence and retrieval on etcd.
 */
class EnvironmentService
{
    private const KEY_PREFIX = '/clah/env/';

    public function __construct(private readonly EtcdClient $etcdClient)
    {
    }

    /**
     * Persist one environment payload.
     *
     * @param array<string, mixed> $payload
     */
    public function save(array $payload): string
    {
        $id = isset($payload['id']) && is_string($payload['id']) && $payload['id'] !== ''
            ? $payload['id']
            : $this->generateId();

        $createdAt = isset($payload['created_at']) && is_string($payload['created_at'])
            ? $payload['created_at']
            : (new DateTimeImmutable())->format(DATE_ATOM);

        $record = [
            'id' => $id,
            'name' => (string) ($payload['name'] ?? ''),
            'description' => isset($payload['description']) ? (string) $payload['description'] : null,
            'created_at' => $createdAt,
            'attributes' => is_array($payload['attributes'] ?? null) ? $payload['attributes'] : [],
        ];

        $this->etcdClient->put($this->key($id), $record);

        return $id;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(string $id): ?array
    {
        return $this->etcdClient->get($this->key($id));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->etcdClient->getByPrefix(self::KEY_PREFIX);
    }

    public function delete(string $id): void
    {
        $this->etcdClient->delete($this->key($id));
    }

    private function generateId(): string
    {
        return bin2hex(random_bytes(16));
    }

    private function key(string $id): string
    {
        return self::KEY_PREFIX . $id;
    }
}
