<?php

namespace App\Repository\DataTable;

use App\Entity\Product;
use App\Interface\DataTableRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class ProductDataTableRepository extends AbstractDataTableRepository implements DataTableRepositoryInterface
{
    public function getEntityClass(): string
    {
        return Product::class;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        $qb = new QueryBuilder($this->getEntityManager());
        $qb->from(Product::class, 'p')
            ->select(
                'p.uuid AS uuid',
                'p.id AS id',
                'p.name AS name',
                'p.description AS description',
                'p.image AS image',
                'p.price AS price',
                'p.stock AS stock',
            );

        return $qb;
    }
}