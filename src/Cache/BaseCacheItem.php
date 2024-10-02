<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Cache;

use Psr\Cache\CacheItemInterface;

class BaseCacheItem implements CacheItemInterface
{
    private string $key;
    private mixed $value;
    private ?\DateTimeInterface $expires_at;
    private \DateTimeInterface|int|null $expires_after;

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
        // TODO: Implement isHit() method.
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