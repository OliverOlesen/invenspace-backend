<?php

namespace App\Model;

use App\Model\AbstractLineModel;

class OrderLineModel extends AbstractLineModel
{
    public ?string $orderUuid = null;
}