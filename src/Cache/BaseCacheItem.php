<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Cache;

use Psr\Cache\CacheItemInterface;

class BaseCacheItem implements CacheItemInterface
{
    public function __construct(
        private readonly string             $key,
        private mixed                       $value,
        private readonly bool               $is_hit,
        private ?\DateTimeInterface         $expires_at = null,
        private \DateTimeInterface|int|null $expires_after = null
    )
    {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function get(): mixed
    {
        return $this->value;
    }

    public function isHit(): bool
    {
        return $this->is_hit;
    }

    public function set(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function expiresAt(?\DateTimeInterface $expiration): static
    {
        $this->expires_at = $expiration;

        return $this;
    }

    public function expiresAfter(\DateInterval|int|null $time): static
    {
        $this->expires_after = $time;

        return $this;
    }
}