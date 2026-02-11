<?php

declare(strict_types=1);

namespace App\Domain\Network;

use App\Services\Network\NetworkService;
use RuntimeException;

/**
 * Network entity.
 *
 * new Network() => empty entity to create.
 * new Network($id, $environmentId) => loads existing entity data.
 */
class Network
{
    private ?string $id = null;

    private string $environmentId = '';

    private string $name = '';

    private string $type = 'bridge';

    private string $subnet = '';

    private string $gateway = '';

    private bool $provider = false;

    private ?string $createdAt = null;

    /**
     * @var array<string, mixed>
     */
    private array $attributes = [];

    public function __construct(?string $id = null, ?string $environmentId = null)
    {
        if ($id === null || $id === '' || $environmentId === null || $environmentId === '') {
            return;
        }

        $payload = app(NetworkService::class)->findById($id, $environmentId);
        if ($payload === null) {
            return;
        }

        $this->hydrate($payload);
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function environmentId(): string
    {
        return $this->environmentId;
    }

    public function setEnvironmentId(string $environmentId): void
    {
        $this->environmentId = $environmentId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function subnet(): string
    {
        return $this->subnet;
    }

    public function setSubnet(string $subnet): void
    {
        $this->subnet = $subnet;
    }

    public function gateway(): string
    {
        return $this->gateway;
    }

    public function setGateway(string $gateway): void
    {
        $this->gateway = $gateway;
    }

    public function isProvider(): bool
    {
        return $this->provider;
    }

    public function setProvider(bool $provider): void
    {
        $this->provider = $provider;
    }

    public function createdAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function save(): string
    {
        $this->assertRequiredFields();

        $id = app(NetworkService::class)->save($this->toArray());

        if ($this->id === null) {
            $this->id = $id;
            $reloaded = app(NetworkService::class)->findById($id, $this->environmentId);
            if ($reloaded !== null) {
                $this->hydrate($reloaded);
            }
        }

        return $id;
    }

    public function delete(): void
    {
        if ($this->id === null || $this->environmentId === '') {
            return;
        }

        app(NetworkService::class)->delete($this->id, $this->environmentId);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'environment_id' => $this->environmentId,
            'name' => $this->name,
            'type' => $this->type,
            'subnet' => $this->subnet,
            'gateway' => $this->gateway,
            'provider' => $this->provider,
            'created_at' => $this->createdAt,
            'attributes' => $this->attributes,
        ];
    }

    private function assertRequiredFields(): void
    {
        if ($this->environmentId === '') {
            throw new RuntimeException('Environment id is required.');
        }

        if ($this->name === '') {
            throw new RuntimeException('Network name is required.');
        }

        if ($this->subnet === '') {
            throw new RuntimeException('Network subnet is required.');
        }

        if ($this->gateway === '') {
            throw new RuntimeException('Network gateway is required.');
        }
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function hydrate(array $payload): void
    {
        $this->id = isset($payload['id']) ? (string) $payload['id'] : null;
        $this->environmentId = (string) ($payload['environment_id'] ?? '');
        $this->name = (string) ($payload['name'] ?? '');
        $this->type = (string) ($payload['type'] ?? 'bridge');
        $this->subnet = (string) ($payload['subnet'] ?? '');
        $this->gateway = (string) ($payload['gateway'] ?? '');
        $this->provider = (bool) ($payload['provider'] ?? false);
        $this->createdAt = isset($payload['created_at']) ? (string) $payload['created_at'] : null;
        $this->attributes = is_array($payload['attributes'] ?? null) ? $payload['attributes'] : [];
    }
}
