<?php

namespace App\Service\Grid;

use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
    public function apply(QueryBuilder $queryBuilder, string $filter): QueryBuilder;

    /**
     * @return array<string>
     */
    public function getFilters(): array;
}
