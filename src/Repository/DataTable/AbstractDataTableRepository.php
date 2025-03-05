<?php

namespace App\Repository\DataTable;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractDataTableRepository
{
    private EntityManagerInterface $em;
    private Security $security;
    public function __construct(
        EntityManagerInterface $em,
        Security $security,
    ) {
      $this->em = $em;
      $this->security = $security;
    }

    abstract function getQueryBuilder(): QueryBuilder;
    abstract function getEntityClass(): string;

    protected function getUser() :UserInterface {
        return $this->security->getUser();
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }
}