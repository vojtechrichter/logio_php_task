<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Services;

use Nette\Neon\Exception;
use Nette\Neon\Neon;

final class ConfigService
{
    private mixed $decoded_config_file;

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
}