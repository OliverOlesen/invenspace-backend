<?php

namespace App\Model;

use App\Entity\CartLine;
use App\Model\AbstractLineModel;

class CartLineModel extends AbstractLineModel
{
    public ?string $cartUuid = null;
}