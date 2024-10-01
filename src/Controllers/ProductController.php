<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Controllers;

use Nette\Neon\Exception;
use Nette\Neon\Neon;
use Vojtechrichter\LogioPhpTask\Http\ServerHttpResponse;
use Vojtechrichter\LogioPhpTask\Model\Elastic\ElasticSearchDriver;
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
        if ($this->config_service->keyExists('product_database')) {
            $config_database_type = $this->config_service->getDecodedConfigFile()['product_database'];

            $db_driver = match ($config_database_type) {
                'mysql' => new MySqlDriver(),
                'elastic' => new ElasticSearchDriver(),

                default => (object)null,
            };

            // check for cache hit
            if ($db_driver !== null) {
                if (is_numeric($id)) {
                    $product = $db_driver->getProductById(intval($id));

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
}