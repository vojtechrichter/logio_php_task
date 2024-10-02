<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Controllers;

use Vojtechrichter\LogioPhpTask\Model\Elastic\ElasticSearchDriver;
use Vojtechrichter\LogioPhpTask\Model\IProductQuery;
use Vojtechrichter\LogioPhpTask\Model\IProductQueryDriver;
use Vojtechrichter\LogioPhpTask\Model\MySql\MySqlDriver;
use Vojtechrichter\LogioPhpTask\Services\ConfigService;

final readonly class ProductController
{
    public function __construct(
        private ConfigService $config_service
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function getDetail(string $id): string
    {
        // TODO: check for cache hit

        if ($this->config_service->keyExists(ConfigService::PRODUCT_DATABASE)) {
            $db_driver = $this->getDbDriverFromConfig($this->config_service->getDecodedConfigFile()[ConfigService::PRODUCT_DATABASE]);

            if ($db_driver !== null) {
                if (is_numeric($id)) {
                    // TODO: increment the query count
                    $product = $db_driver->findProductById(intval($id));

                    return json_encode($product);
                } else {
                    throw new \Exception('Product ID is not a numeric value');
                }
            } else {
                throw new \Exception('Database driver is not set');
            }
        }

        throw new \Exception('Config key \'product_database\' is not set');
    }

    private function getDbDriverFromConfig(string $config_db_type): ?IProductQueryDriver
    {
        return match ($config_db_type) {
            'mysql' => new MySqlDriver(),
            'elastic' => new ElasticSearchDriver(),

            default => null,
        };
    }
}