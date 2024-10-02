<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Services;

use Nette\Neon\Neon;

final class ConfigService
{
    private mixed $decoded_config_file;

    public const string PRODUCT_DATABASE = 'product_database';
    public const string PRODUCT_CACHE = 'product_cache';
    public const string CACHING_MECHANISM = 'caching_mechanism';
    public const string FILE_CACHE_LOCATION = 'file_cache_location';
    public const string PRODUCT_QUERY_COUNT = 'product_query_count';
    public const string STORAGE_TYPE = 'storage_type';
    public const string FILE_STORAGE_LOCATION = 'file_storage_location';

    public function __construct(
        private string $config_file_path
    )
    {
        try {
            $this->decoded_config_file = Neon::decodeFile($this->config_file_path);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    public function getDecodedConfigFile(): mixed
    {
        return $this->decoded_config_file;
    }

    public function keyExists(string $key, array $collection = null): bool
    {
        if ($collection === null) {
            $collection = $this->decoded_config_file;
        }

        foreach ($collection as $record => $value) {
            if (is_array($record)) {
                return $this->keyExists($key, $record);
            } else {
                if ($key === $record) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getDatabaseType(): string
    {

    }
}