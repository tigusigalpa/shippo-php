<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\DTOs;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final readonly class PaginatedCollection implements IteratorAggregate, Countable
{
    public function __construct(
        public array $results,
        public ?string $next = null,
        public ?string $previous = null,
        public ?int $count = null,
    ) {
    }

    public static function fromArray(array $data, callable $itemMapper): self
    {
        $results = array_map($itemMapper, $data['results'] ?? []);

        return new self(
            results: $results,
            next: $data['next'] ?? null,
            previous: $data['previous'] ?? null,
            count: $data['count'] ?? null,
        );
    }

    public function hasMorePages(): bool
    {
        return $this->next !== null;
    }

    public function hasPreviousPages(): bool
    {
        return $this->previous !== null;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->results);
    }

    public function count(): int
    {
        return count($this->results);
    }

    public function toArray(): array
    {
        return [
            'results' => $this->results,
            'next' => $this->next,
            'previous' => $this->previous,
            'count' => $this->count,
        ];
    }
}
