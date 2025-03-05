<?php

namespace App\Repository\DataTable;

use App\Entity\User;
use App\Interface\DataTableRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserDataTableRepository extends AbstractDataTableRepository implements DataTableRepositoryInterface
{

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        /** @var User $user */
        $user = $this->getUser();

        $qb = new QueryBuilder($this->getEntityManager());
        $qb->from(User::class, 'u')
            ->select(
                'u.uuid AS uuid',
                'u.id AS id',
                'u.username AS username',
                'u.email AS email',
                'u.roles AS roles',
            )
            ->where('u.uuid != :uuid')
            ->setParameter('uuid', $user->getUuid());

        return $qb;
    }
}