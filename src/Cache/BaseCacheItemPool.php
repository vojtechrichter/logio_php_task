<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

abstract class BaseCacheItemPool implements CacheItemPoolInterface
{
    abstract public function __construct(
        string $cache_dir_path = __DIR__ . '/../../tmp/product_cache'
    );

    abstract public function getItem(string $key): CacheItemInterface;

    abstract public function getItems(array $keys = []): iterable;

    abstract public function hasItem(string $key): bool;

    abstract public function clear(): bool;

    abstract public function deleteItem(string $key): bool;

    abstract public function deleteItems(array $keys): bool;

    abstract public function save(CacheItemInterface $item): bool;

    abstract public function saveDeferred(CacheItemInterface $item): bool;

    abstract public function commit(): bool;
}