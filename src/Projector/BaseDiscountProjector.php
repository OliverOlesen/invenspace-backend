<?php

namespace App\Projector;

use App\Entity\BaseDiscount;
use App\Event\BaseDiscount\BaseDiscountCreated;
use App\Model\BaseDiscountModel;
use App\Projector\AbstractProjector;
use Doctrine\ORM\EntityManagerInterface;

class BaseDiscountProjector extends AbstractProjector
{
    public function __construct(EntityManagerInterface $entityManager, ProductProjector $productInfoProjector)
    {
        parent::__construct($entityManager);
    }

    public function ApplyBaseDiscountCreated(BaseDiscountCreated $event): BaseDiscount|false
    {
        /** @var BaseDiscountModel $model */
        $model = $event->getData();

        if ($model instanceof BaseDiscountModel)
        {
            $baseDiscount = (new BaseDiscount())
                ->setFrom($model->from)
                ->setTo($model->to)
                ->setPercentage($model->percentage);

            $this->getEntityManager()->persist($baseDiscount);
            $this->getEntityManager()->flush();

            return $baseDiscount;
        }

        return false;
    }
}