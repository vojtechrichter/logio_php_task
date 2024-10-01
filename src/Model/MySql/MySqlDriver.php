<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Model\MySql;

use Vojtechrichter\LogioPhpTask\Model\IProductQuery;

final class MySqlDriver implements IProductQuery
{
    public function findProductById(int $id): array
    {
        // TODO: Implement findProductById() method.
    }
}