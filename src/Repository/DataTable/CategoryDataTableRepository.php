<?php

namespace App\Repository\DataTable;

use App\Entity\Category;
use App\Interface\DataTableRepositoryInterface;
use App\Repository\DataTable\AbstractDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class CategoryDataTableRepository extends AbstractDataTableRepository implements DataTableRepositoryInterface
{
    function getEntityClass(): string
    {
        return Category::class;
    }

    function getQueryBuilder(): QueryBuilder
    {
        $qb = new QueryBuilder($this->getEntityManager());
        $qb->from(Category::class, 'c')
            ->select(
                'c.uuid AS uuid',
                'c.id AS id',
                'c.name AS name',
                'c.description AS description',
                'c.type AS type',
            );

        return $qb;
    }


}