<?php

namespace App\Repository\DataTable;

use App\Entity\Contact;
use App\Interface\DataTableRepositoryInterface;
use App\Repository\DataTable\AbstractDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class ContactDataTableRepository extends AbstractDataTableRepository implements DataTableRepositoryInterface
{
    public function getEntityClass(): string
    {
        return Contact::class;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        $qb = new QueryBuilder($this->getEntityManager());
        $qb->from(Contact::class, 'c')
            ->select(
                'c.uuid AS uuid',
                'c.id AS id',
                'c.firstName AS firstName',
                'c.middleName AS middleName',
                'c.lastName AS lastName',
                'c.email AS email',
                'c.mobile AS mobile',
            );

        return $qb;
    }
}