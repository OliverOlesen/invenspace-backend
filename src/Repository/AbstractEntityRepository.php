<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityRepository;

class AbstractEntityRepository extends EntityRepository
{
    public function findOneByUuid(string $uuid)
    {
        return $this
            ->findOneBy(['uuid' => $uuid]);
    }

    public function findOneById(string $id)
    {
        return $this
            ->findOneBy(['id' => $id]);
    }
}