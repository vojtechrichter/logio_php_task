<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Model\MySql;

use Vojtechrichter\LogioPhpTask\Model\IProductQueryDriver;

final class MySqlDriver implements IProductQueryDriver
{
    public function findProductById(int $id): array
    {
        // query mysql database to find the product

        return [];
    }
}