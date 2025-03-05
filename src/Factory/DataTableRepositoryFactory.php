<?php

namespace App\Factory;

use App\Repository\DataTable\AbstractDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class DataTableRepositoryFactory
{
    private iterable $repositories;

    public function __construct(
        iterable $repositories,
        private readonly EntityManagerInterface $em,
        private readonly Security $security
    ){
        $this->repositories = $repositories;
    }

    public function getDataTableRepositoryForType(string $type): ?AbstractDataTableRepository
    {
        foreach ($this->repositories as $repository) {
            if ($repository->getEntityClass() === $type) {
                return new $repository($this->em, $this->security);
            }
        }
        return null;
    }
}