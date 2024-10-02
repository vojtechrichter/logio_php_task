<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Model\Elastic;

use Vojtechrichter\LogioPhpTask\Model\IProductQueryDriver;

class ElasticSearchDriver implements IProductQueryDriver
{
    public function findProductById(int $id): array
    {
        // query elastic for the product

        return [];
    }
}