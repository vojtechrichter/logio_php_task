<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Cache\Memcached;

use Psr\Cache\CacheItemInterface;
use Vojtechrichter\LogioPhpTask\Cache\BaseCacheItemPool;

final class MemcachedCacheItemPool extends BaseCacheItemPool
{
    private string $cache_dir_path;

    public function __construct(
        string $cache_dir_path = __DIR__ . '/../../tmp/product_cache'
    )
    {
        $this->cache_dir_path = $cache_dir_path;
    }

    #[\Override]
    public function getItem(string $key): CacheItemInterface
    {
        // TODO: Implement getItem() method.
    }

    public function getItems(array $keys = []): iterable
    {
        // TODO: Implement getItems() method.
    }

    public function hasItem(string $key): bool
    {
        // TODO: Implement hasItem() method.
    }

    public function clear(): bool
    {
        // TODO: Implement clear() method.
    }

    public function deleteItem(string $key): bool
    {
        // TODO: Implement deleteItem() method.
    }

    public function deleteItems(array $keys): bool
    {
        // TODO: Implement deleteItems() method.
    }

    public function save(CacheItemInterface $item): bool
    {
        // TODO: Implement save() method.
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        // TODO: Implement saveDeferred() method.
    }

    public function commit(): bool
    {
        // TODO: Implement commit() method.
    }
}