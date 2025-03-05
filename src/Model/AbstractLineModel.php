<?php

namespace App\Model;

use App\Entity\AbstractLine;
use App\Entity\CartLine;
use App\Entity\OrderLine;

class AbstractLineModel extends AbstractModel
{
    public ?string $productUuid = null;
    public int $amount;

    public static function createFromLine(AbstractLine $lineEntity): CartLineModel|OrderLineModel
    {

        $lineModel = $lineEntity instanceof CartLine ? new OrderLineModel() : new CartLineModel();
        $lineModel->productUuid = $lineEntity->getProduct()->getUuid();
        $lineModel->amount = $lineEntity->getAmount();


        switch ($lineEntity::class) {
            case OrderLine::class:
                $lineModel->orderUuid = $lineEntity->getOrder()->getUuid();
                break;

            case CartLine::class:
                $lineModel->cartUuid = $lineEntity->getCart()->getUuid();
                break;
        }

        return $lineModel;
    }
}