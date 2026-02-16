<?php

declare(strict_types=1);

use Tigusigalpa\Shippo\DTOs\PaginatedCollection;

test('paginated collection can be created from array', function () {
    $data = [
        'results' => [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
        ],
        'next' => 'https://api.example.com/page2',
        'previous' => null,
        'count' => 10,
    ];

    $collection = PaginatedCollection::fromArray($data, fn($item) => $item);

    expect($collection->results)->toHaveCount(2)
        ->and($collection->next)->toBe('https://api.example.com/page2')
        ->and($collection->previous)->toBeNull()
        ->and($collection->count)->toBe(10);
});

test('paginated collection can detect more pages', function () {
    $collection = new PaginatedCollection(
        results: [],
        next: 'https://api.example.com/page2'
    );

    expect($collection->hasMorePages())->toBeTrue();
});

test('paginated collection can detect no more pages', function () {
    $collection = new PaginatedCollection(
        results: [],
        next: null
    );

    expect($collection->hasMorePages())->toBeFalse();
});

test('paginated collection is countable', function () {
    $collection = new PaginatedCollection(
        results: [1, 2, 3, 4, 5]
    );

    expect($collection)->toHaveCount(5)
        ->and(count($collection))->toBe(5);
});

test('paginated collection is iterable', function () {
    $items = ['a', 'b', 'c'];
    $collection = new PaginatedCollection(results: $items);

    $result = [];
    foreach ($collection as $item) {
        $result[] = $item;
    }

    expect($result)->toBe($items);
});
