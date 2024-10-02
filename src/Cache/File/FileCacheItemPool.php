<?php

namespace Vojtechrichter\LogioPhpTask\Cache\File;

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Exceptions\PhpfastcacheDriverCheckException;
use Phpfastcache\Exceptions\PhpfastcacheDriverException;
use Phpfastcache\Exceptions\PhpfastcacheDriverNotFoundException;
use Phpfastcache\Exceptions\PhpfastcacheExtensionNotInstalledException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidTypeException;
use Phpfastcache\Exceptions\PhpfastcacheLogicException;
use Psr\Cache\CacheItemInterface;
use Vojtechrichter\LogioPhpTask\Cache\BaseCacheItemPool;
use Vojtechrichter\LogioPhpTask\Exceptions\CacheException;

final class FileCacheItemPool extends BaseCacheItemPool
{
    private ExtendedCacheItemPoolInterface $cache_instance;

    /**
     * @throws PhpfastcacheInvalidTypeException
     * @throws PhpfastcacheInvalidArgumentException
     * @throws PhpfastcacheInvalidConfigurationException
     * @throws CacheException
     */
    public function __construct(
        string $cache_dir_path = __DIR__ . '/../../../tmp/product_cache'
    )
    {
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => $cache_dir_path,
        ]));

        try {
            $this->cache_instance = CacheManager::getInstance('files');
        } catch (PhpfastcacheDriverCheckException|PhpfastcacheExtensionNotInstalledException|PhpfastcacheDriverNotFoundException|PhpfastcacheDriverException|PhpfastcacheLogicException $e) {
            throw new CacheException($e->getMessage());
        }
    }

    public function getItem(string $key): CacheItemInterface
    {
        return $this->cache_instance->getItem($key);
    }

    public function getItems(array $keys = []): iterable
    {
        return $this->cache_instance->getItems($keys);
    }

    public function hasItem(string $key): bool
    {
        return $this->cache_instance->hasItem($key);
    }

    public function clear(): bool
    {
        $this->cache_instance->clear();
    }

    public function deleteItem(string $key): bool
    {
        $this->cache_instance->deleteItem($key);
    }

    public function deleteItems(array $keys): bool
    {
        return $this->cache_instance->deleteItems($keys);
    }

    public function save(CacheItemInterface $item): bool
    {
        return $this->cache_instance->save($item);
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        return $this->cache_instance->saveDeferred($item);
    }

    public function commit(): bool
    {
        return $this->cache_instance->commit();
    }
}