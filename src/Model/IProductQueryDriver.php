<?php

declare(strict_types=1);

namespace Vojtechrichter\LogioPhpTask\Model;

interface IProductQueryDriver
{
    public function findProductById(int $id): array;
}