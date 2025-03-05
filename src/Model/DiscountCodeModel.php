<?php

namespace App\Model;

use App\Entity\DiscountCode;
use DateTime;

class DiscountCodeModel extends AbstractModel
{
    public DateTime $from;
    public DateTime $to;
    public int $percentage;
    public string $code = '';

    public static function createFromDiscountCode(DiscountCode $discountCode): self
    {
        $model = new self();
        $model->from = $discountCode->getFrom();
        $model->to = $discountCode->getTo();
        $model->percentage = $discountCode->getPercentage();
        $model->code = $discountCode->getCode();

        return $model;
    }
}