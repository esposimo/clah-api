<?php

declare(strict_types=1);

namespace App\Domain;

use Illuminate\Support\Str;

final class Environment
{
    private string $id;
    private ?string $name = null;
    private ?string $description = null;
    private ?string $friendlyName = null;

    public function __construct(array $attributes)
    {
        $this->id = isset($attributes['id']) && is_string($attributes['id'])
            ? $attributes['id']
            : (string) Str::uuid();

        if (isset($attributes['name']) && is_string($attributes['name'])) {
            $this->setName($attributes['name']);
        }

        if (isset($attributes['description']) && is_string($attributes['description'])) {
            $this->setDescription($attributes['description']);
        }

        if (isset($attributes['friendly-name']) && is_string($attributes['friendly-name'])) {
            $this->setFriendlyName($attributes['friendly-name']);
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getFriendlyName(): ?string
    {
        return $this->friendlyName;
    }

    public function setFriendlyName(string $friendlyName): void
    {
        $this->friendlyName = $friendlyName;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'friendly-name' => $this->friendlyName,
        ];
    }
}
