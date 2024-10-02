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
    #[\Override]
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

    #[\Override]
    public function getItem(string $key): CacheItemInterface
    {
        return $this->cache_instance->getItem($key);
    }

    #[\Override]
    public function getItems(array $keys = []): iterable
    {
        return $this->cache_instance->getItems($keys);
    }

    #[\Override]
    public function hasItem(string $key): bool
    {
        return $this->cache_instance->hasItem($key);
    }

    #[\Override]
    public function clear(): bool
    {
        return $this->cache_instance->clear();
    }

    #[\Override]
    public function deleteItem(string $key): bool
    {
        return $this->cache_instance->deleteItem($key);
    }

    #[\Override]
    public function deleteItems(array $keys): bool
    {
        return $this->cache_instance->deleteItems($keys);
    }

    #[\Override]
    public function save(CacheItemInterface $item): bool
    {
        return $this->cache_instance->save($item);
    }

    #[\Override]
    public function saveDeferred(CacheItemInterface $item): bool
    {
        return $this->cache_instance->saveDeferred($item);
    }

    #[\Override]
    public function commit(): bool
    {
        return $this->cache_instance->commit();
    }
}