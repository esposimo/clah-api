<?php

declare(strict_types=1);

namespace App\Domain\Network;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Array collector of Network entities within one environment.
 *
 * @implements IteratorAggregate<int, Network>
 */
class NetworkItem implements IteratorAggregate, Countable
{
    /**
     * @var array<int, Network>
     */
    private array $items = [];

    public function __construct(private readonly string $environmentId)
    {
    }

    public function environmentId(): string
    {
        return $this->environmentId;
    }

    public function add(Network $network): void
    {
        if ($network->environmentId() !== $this->environmentId) {
            return;
        }

        $this->items[] = $network;
    }

    /**
     * @return array<int, Network>
     */
    public function all(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return Traversable<int, Network>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(
            static fn (Network $network): array => $network->toArray(),
            $this->items,
        );
    }
}
