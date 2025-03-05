<?php

namespace App\Repository\DataTable;

use App\Entity\Order;
use App\Interface\DataTableRepositoryInterface;
use App\Repository\DataTable\AbstractDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class OrderDataTableRepository extends AbstractDataTableRepository implements DataTableRepositoryInterface
{
    public function getEntityClass(): string
    {
        return Order::class;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        $qb = new QueryBuilder($this->getEntityManager());
        $qb->from(Order::class, 'o')
            ->join('o.contact', 'ct')
            ->select(
                'o.uuid AS uuid',
                'o.id AS id',
                'o.createdAt AS createdAt',
                'ct.firstName AS firstName',
                'ct.lastName AS lastName',
            );

        return $qb;
    }
}