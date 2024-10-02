<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Services;

final class ProductQueryCountService
{
    public function __construct(
        private \Memcached $memcached
    )
    {
    }

    public function updateProductQueryCount(int $product_id, int $by = 1): void
    {
        if ($this->memcached->get($product_id) !== false) {
            $this->memcached->set($product_id, $this->memcached->get($product_id) + $by);
        } else {
            $this->memcached->set($product_id, 1);
        }
    }
}