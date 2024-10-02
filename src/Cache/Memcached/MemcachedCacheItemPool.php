<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Cache\Memcached;

use Memcached;
use Psr\Cache\CacheItemInterface;
use Vojtechrichter\LogioPhpTask\Cache\BaseCacheItem;
use Vojtechrichter\LogioPhpTask\Cache\BaseCacheItemPool;

final class MemcachedCacheItemPool extends BaseCacheItemPool
{
    private string $cache_dir_path;
    private Memcached $memcached;

    /** @var CacheItemInterface[] $deferred_items */
    private array $deferred_items = [];

    #[\Override]
    public function __construct(
        string $cache_dir_path = __DIR__ . '/../../tmp/product_cache'
    )
    {
        $this->cache_dir_path = $cache_dir_path;

        $this->memcached = new Memcached();
        $this->memcached->addServer('127.0.0.1', 11211); // example server
    }

    #[\Override]
    public function getItem(string $key): CacheItemInterface
    {
        $res_value = $this->memcached->get($key);
        if ($res_value === false || $this->memcached->getResultCode() === Memcached::RES_NOTFOUND) {
            return new BaseCacheItem(
                $key,
                null,
                false
            );
        } else {
            return new BaseCacheItem(
                $key,
                $res_value,
                true
            );
        }
    }

    #[\Override]
    public function getItems(array $keys = []): iterable
    {
        return $this->memcached->getAllKeys();
    }

    #[\Override]
    public function hasItem(string $key): bool
    {
        return $this->memcached->get($key) !== false;
    }

    #[\Override]
    public function clear(): bool
    {
        return $this->memcached->flush();
    }

    #[\Override]
    public function deleteItem(string $key): bool
    {
        return $this->memcached->delete($key);
    }

    #[\Override]
    public function deleteItems(array $keys): bool
    {
        $result = true;
        foreach ($keys as $key) {
            if ($result) {
                $result = $this->memcached->delete($key);
            } else {
                $this->memcached->delete($key);
            }
        }

        return $result;
    }

    #[\Override]
    public function save(CacheItemInterface $item): bool
    {
        return $this->memcached->set($item->getKey(), $item->get());
    }

    #[\Override]
    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->deferred_items[] = $item;

        return true;
    }

    #[\Override]
    public function commit(): bool
    {
        $result = true;
        foreach ($this->deferred_items as $item) {
            if ($result) {
                $result = $this->memcached->set($item->getKey(), $item->get());
            } else {
                $this->memcached->set($item->getKey(), $item->get());
            }
        }

        return $result;
    }
}