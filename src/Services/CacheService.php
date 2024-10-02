<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Services;

final class CacheService
{
    public static function getProductCacheFileHash(string $product_id): string
    {
        $hash = hash('sha512', 'product_' . $product_id);

        return $hash;
    }
}