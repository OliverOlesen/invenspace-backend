<?php

namespace App\Repository\DataTable;

use App\Entity\Address;
use App\Interface\DataTableRepositoryInterface;
use App\Repository\DataTable\AbstractDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class AddressDataTableRepository extends AbstractDataTableRepository implements DataTableRepositoryInterface
{
    public function getEntityClass(): string
    {
        return Address::class;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        $qb = new QueryBuilder($this->getEntityManager());
        $qb->from(Address::class, 'a')
            ->select(
                'a.uuid AS uuid',
                'a.id AS id',
                'a.country AS country',
                'a.state AS state',
                'a.city AS city',
                'a.postalCode AS postalCode',
                'a.street AS street',
                'a.houseNumber AS houseNumber'
            );

        return $qb;
    }
}