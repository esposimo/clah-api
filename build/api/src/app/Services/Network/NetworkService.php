<?php

declare(strict_types=1);

namespace App\Services\Network;

use App\Services\Etcd\EtcdClient;
use DateTimeImmutable;
use RuntimeException;

/**
 * Handles network persistence and retrieval on etcd.
 */
class NetworkService
{
    public function __construct(private readonly EtcdClient $etcdClient)
    {
    }

    /**
     * Persist one network payload for one environment.
     *
     * @param array<string, mixed> $payload
     */
    public function save(array $payload): string
    {
        $environmentId = (string) ($payload['environment_id'] ?? '');
        if ($environmentId === '') {
            throw new RuntimeException('Environment id is required.');
        }

        $id = isset($payload['id']) && is_string($payload['id']) && $payload['id'] !== ''
            ? $payload['id']
            : $this->generateId();

        $provider = (bool) ($payload['provider'] ?? false);
        $this->assertSingleProvider($environmentId, $id, $provider);

        $createdAt = isset($payload['created_at']) && is_string($payload['created_at'])
            ? $payload['created_at']
            : (new DateTimeImmutable())->format(DATE_ATOM);

        $record = [
            'id' => $id,
            'environment_id' => $environmentId,
            'name' => (string) ($payload['name'] ?? ''),
            'type' => (string) ($payload['type'] ?? 'bridge'),
            'subnet' => (string) ($payload['subnet'] ?? ''),
            'gateway' => (string) ($payload['gateway'] ?? ''),
            'provider' => $provider,
            'attributes' => is_array($payload['attributes'] ?? null) ? $payload['attributes'] : [],
            'created_at' => $createdAt,
        ];

        $this->etcdClient->put($this->key($environmentId, $id), $record);

        return $id;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(string $id, string $environmentId): ?array
    {
        return $this->etcdClient->get($this->key($environmentId, $id));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function allByEnvironment(string $environmentId): array
    {
        return $this->etcdClient->getByPrefix($this->prefix($environmentId));
    }

    public function delete(string $id, string $environmentId): void
    {
        $this->etcdClient->delete($this->key($environmentId, $id));
    }

    private function assertSingleProvider(string $environmentId, string $id, bool $provider): void
    {
        if (!$provider) {
            return;
        }

        foreach ($this->allByEnvironment($environmentId) as $network) {
            $storedId = (string) ($network['id'] ?? '');
            $storedProvider = (bool) ($network['provider'] ?? false);

            if ($storedProvider && $storedId !== $id) {
                throw new RuntimeException('Only one provider network is allowed per environment.');
            }
        }
    }

    private function generateId(): string
    {
        return bin2hex(random_bytes(16));
    }

    private function prefix(string $environmentId): string
    {
        return sprintf('/clah/env/%s/network/', $environmentId);
    }

    private function key(string $environmentId, string $id): string
    {
        return $this->prefix($environmentId) . $id;
    }
}
