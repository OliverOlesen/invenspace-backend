<?php

namespace App\Repository\DataTable;

use App\Entity\OrderLine;
use App\Interface\DataTableRepositoryInterface;
use App\Repository\DataTable\AbstractDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class OrderLineDataTableRepository extends AbstractDataTableRepository implements DataTableRepositoryInterface
{
    public function getEntityClass(): string
    {
        return OrderLine::class;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        $qb = new QueryBuilder($this->getEntityManager());
        $qb->from(OrderLine::class, 'ol')
            ->join('ol.order', 'o')
            ->join('ol.product', 'p')
            ->select(
                'o.uuid AS uuid',
                'o.id AS id',
                'o.createdAt AS createdAt',
                'p.name AS product',
                'p.price AS price',
            );

        return $qb;
    }
}