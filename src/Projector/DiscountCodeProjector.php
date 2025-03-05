<?php

namespace App\Projector;

use App\Entity\DiscountCode;
use App\Event\DiscountCode\DiscountCodeCreated;
use App\Model\DiscountCodeModel;

class DiscountCodeProjector extends AbstractProjector
{
    public function ApplyDiscountCodeCreated(DiscountCodeCreated $event): DiscountCode|false
    {
        /** @var DiscountCodeModel $model */
        $model = $event->getData();

        if ($model instanceof DiscountCodeModel)
        {
            $discountCode = (new DiscountCode())
                ->setFrom($model->from)
                ->setTo($model->to)
                ->setPercentage($model->percentage)
                ->setCode($model->code);

            $this->getEntityManager()->persist($discountCode);
            $this->getEntityManager()->flush();

            return $discountCode;
        }

        return false;
    }
}