<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Controllers;

use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidTypeException;
use Psr\Cache\InvalidArgumentException;
use Vojtechrichter\LogioPhpTask\Cache\BaseCacheItem;
use Vojtechrichter\LogioPhpTask\Cache\BaseCacheItemPool;
use Vojtechrichter\LogioPhpTask\Cache\File\FileCacheItemPool;
use Vojtechrichter\LogioPhpTask\Cache\Memcached\MemcachedCacheItemPool;
use Vojtechrichter\LogioPhpTask\Exceptions\CacheException;
use Vojtechrichter\LogioPhpTask\Exceptions\ConfigException;
use Vojtechrichter\LogioPhpTask\Model\Elastic\ElasticSearchDriver;
use Vojtechrichter\LogioPhpTask\Model\IProductQuery;
use Vojtechrichter\LogioPhpTask\Model\IProductQueryDriver;
use Vojtechrichter\LogioPhpTask\Model\MySql\MySqlDriver;
use Vojtechrichter\LogioPhpTask\Services\CacheService;
use Vojtechrichter\LogioPhpTask\Services\ConfigService;
use Vojtechrichter\LogioPhpTask\Services\ProductQueryCountService;

final readonly class ProductController
{
    public function __construct(
        private ConfigService            $config_service,
        private ProductQueryCountService $product_query_count_file_service,
    )
    {
    }

    /**
     * @throws \Exception
     * @throws InvalidArgumentException
     * @throws CacheException
     */
    public function getDetail(string $id): string
    {
        if ($this->config_service->keyExists(ConfigService::PRODUCT_CACHE)) {
            if ($this->config_service->keyExists(ConfigService::CACHING_MECHANISM)) {
                $cache_item_pool = $this->getCacheHandlerFromConfig($this->config_service->getDecodedConfigFile()[ConfigService::CACHING_MECHANISM]);

                if ($cache_item_pool->hasItem(CacheService::getProductCacheFileHash($id))) {
                    return json_encode($cache_item_pool->getItem(CacheService::getProductCacheFileHash($id))->get());
                } else {
                    if ($this->config_service->keyExists(ConfigService::PRODUCT_DATABASE)) {
                        $db_driver = $this->getDbDriverFromConfig($this->config_service->getDecodedConfigFile()[ConfigService::PRODUCT_DATABASE]);

                        if ($db_driver !== null) {
                            if (is_numeric($id)) {
                                $product = $db_driver->findProductById(intval($id));
                                $this->product_query_count_file_service->updateProductQueryCount(intval($id));
                                $cache_item_pool->save(new BaseCacheItem(
                                    CacheService::getProductCacheFileHash($id),
                                    $product,
                                    false
                                ));

                                return json_encode($product);
                            } else {
                                throw new \Exception('Product ID is not a numeric value');
                            }
                        } else {
                            throw new ConfigException('Database driver is not set');
                        }
                    } else {
                        throw new ConfigException('Config key \'product_database\' is not set');
                    }
                }
            } else {
                throw new ConfigException('key product_cache::\'caching_mechanism\' is not set');
            }
        } else {
            throw new ConfigException('\'product_cache\' is not set');
        }
    }

    private function getDbDriverFromConfig(string $config_db_type): ?IProductQueryDriver
    {
        return match ($config_db_type) {
            'mysql' => new MySqlDriver(),
            'elastic' => new ElasticSearchDriver(),

            default => null,
        };
    }

    /**
     * @throws ConfigException
     * @throws CacheException
     */
    private function getCacheHandlerFromConfig(string $config_cache_type): ?BaseCacheItemPool
    {
        if ($config_cache_type === 'file') {
            $file_cache_path = __DIR__ . '/../../tmp/product_cache';
            if ($this->config_service->keyExists(ConfigService::FILE_CACHE_LOCATION)) {
                $file_cache_path = $this->config_service->getDecodedConfigFile()[ConfigService::FILE_CACHE_LOCATION];
            } else {
                throw new ConfigException('Config key product_cache::\'file_cache_location\' is not set');
            }

            try {
                return new FileCacheItemPool($file_cache_path);
            } catch (PhpfastcacheInvalidArgumentException|PhpfastcacheInvalidConfigurationException|PhpfastcacheInvalidTypeException|CacheException $e) {
                throw new CacheException($e->getMessage());
            }
        } elseif ($config_cache_type === 'memcached') {
            return new MemcachedCacheItemPool();
        } else {
            return null;
        }
    }
}