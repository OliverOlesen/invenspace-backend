<?php

namespace App\Projector;

use App\Entity\Order;
use App\Event\Order\OrderCreated;
use App\Model\OrderModel;
use App\Projector\AbstractProjector;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class OrderProjector extends AbstractProjector
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function ApplyOrderCreated(OrderCreated $event): Order|false
    {
        /** @var OrderModel $model */
        $model = $event->getData();

        if ($model instanceof OrderModel) {
            $uuid = Uuid::uuid4()->toString();
            $order = (new Order())
            ->setUuid($uuid)
            ->setDiscountCode($model->discountCode);

            $this->getEntityManager()->persist($order);
            $this->getEntityManager()->flush();

            return $order;
        }

        return false;
    }
}