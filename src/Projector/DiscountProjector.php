<?php

namespace App\Projector;

use App\Entity\Discount;
use App\Entity\Product;
use App\Event\Discount\DiscountCreated;
use App\Event\Product\ProductCreated;
use App\Model\DiscountModel;
use App\Model\ProductModel;
use Doctrine\ORM\EntityManagerInterface;

class DiscountProjector extends AbstractProjector
{
    private ProductProjector $productInfoProjector;

    public function __construct(EntityManagerInterface $entityManager, ProductProjector $productInfoProjector)
    {
        parent::__construct($entityManager);
        $this->productInfoProjector = $productInfoProjector;
    }

    public function ApplyDiscountCreated(DiscountCreated $event): Discount|false
    {
        /** @var DiscountModel $model */
        $model = $event->getData();

        if ($model instanceof DiscountModel)
        {
            $discount = (new Discount())
                ->setFrom($model->from)
                ->setTo($model->to)
                ->setPercentage($model->percentage);

            // TODO: Much like with Contact is it correct to call create event here? since the productInfo should already be created, we are just linking it.
            if ($model->product instanceof ProductModel)
            {
                $event = new ProductCreated($model->product);

                $productInfo = $this->productInfoProjector->ApplyProductInfoCreated($event);

                if ($productInfo instanceof Product)
                {
                    $discount->setProduct($productInfo);
                }
            }

            $this->getEntityManager()->persist($discount);
            $this->getEntityManager()->flush();
        }

        return false;
    }
}