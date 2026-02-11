<?php

declare(strict_types=1);

namespace App\Domain\Environment;

use App\Services\Environment\EnvironmentService;

/**
 * Environment entity.
 *
 * new Environment() => empty entity to create.
 * new Environment($id) => loads existing entity data.
 */
class Environment
{
    private ?string $id = null;

    private string $name = '';

    private ?string $description = null;

    private ?string $createdAt = null;

    /**
     * @var array<string, mixed>
     */
    private array $attributes = [];

    public function __construct(?string $id = null)
    {
        if ($id === null || $id === '') {
            return;
        }

        $payload = app(EnvironmentService::class)->findById($id);
        if ($payload === null) {
            return;
        }

        $this->hydrate($payload);
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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
        $id = app(EnvironmentService::class)->save($this->toArray());

        if ($this->id === null) {
            $this->id = $id;
            $reloaded = app(EnvironmentService::class)->findById($id);
            if ($reloaded !== null) {
                $this->hydrate($reloaded);
            }
        }

        return $id;
    }

    public function delete(): void
    {
        if ($this->id === null) {
            return;
        }

        app(EnvironmentService::class)->delete($this->id);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->createdAt,
            'attributes' => $this->attributes,
        ];
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function hydrate(array $payload): void
    {
        $this->id = isset($payload['id']) ? (string) $payload['id'] : null;
        $this->name = (string) ($payload['name'] ?? '');
        $this->description = isset($payload['description']) ? (string) $payload['description'] : null;
        $this->createdAt = isset($payload['created_at']) ? (string) $payload['created_at'] : null;
        $this->attributes = is_array($payload['attributes'] ?? null) ? $payload['attributes'] : [];
    }
}
