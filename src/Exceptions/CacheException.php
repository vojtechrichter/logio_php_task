<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Exceptions;

final readonly class CacheException implements \Psr\Cache\CacheException
{
    public function __construct(
        private string $message,
        private int $code = 0,
        private ?\Throwable $previous = null
    )
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getFile(): string
    {
        return __FILE__;
    }

    public function getLine(): int
    {
        return __LINE__;
    }

    public function getTrace(): array
    {
        return debug_backtrace();
    }

    public function getTraceAsString(): string
    {
        return implode("\n", $this->getTrace());
    }

    public function getPrevious(): ?\Throwable
    {
        return $this->previous;
    }

    public function __toString(): string
    {
        return $this->message;
    }
}