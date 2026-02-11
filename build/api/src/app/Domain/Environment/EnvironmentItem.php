<?php

declare(strict_types=1);

namespace App\Domain\Environment;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Array collector of Environment entities.
 *
 * @implements IteratorAggregate<int, Environment>
 */
class EnvironmentItem implements IteratorAggregate, Countable
{
    /**
     * @var array<int, Environment>
     */
    private array $items = [];

    public function add(Environment $environment): void
    {
        $this->items[] = $environment;
    }

    /**
     * @return array<int, Environment>
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
     * @return Traversable<int, Environment>
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
            static fn (Environment $environment): array => $environment->toArray(),
            $this->items,
        );
    }
}
