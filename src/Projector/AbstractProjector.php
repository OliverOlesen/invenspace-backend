<?php

namespace App\Projector;

use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManagerInterface;

class AbstractProjector
{
    private EntityManagerInterface $entityManager;
    public function __construct(
        EntityManagerInterface $entityManager,
    )
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function save(AbstractEntity $entity, bool $flush = true): void
    {

        $this->entityManager->persist($entity);

        if (!$flush) {
            return;
        }

        $this->entityManager->flush();
    }
}