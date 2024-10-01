<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Model\Elastic;

use Vojtechrichter\LogioPhpTask\Model\IProductQuery;

class ElasticSearchDriver implements IProductQuery
{
    public function findProductById(int $id): array
    {
        // TODO: Implement findProductById() method.
    }
}