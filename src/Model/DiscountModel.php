<?php

namespace App\Model;

use App\Entity\Discount;
use DateTime;
use App\Model\AbstractModel;

class DiscountModel extends AbstractModel
{
    public DateTime $from;
    public DateTime $to;
    public int $percentage;
    public ?ProductModel $product = null;

    public static function createFromDiscount(Discount $discount): self
    {
        $model = new self();
        $model->from = $discount->getFrom();
        $model->to = $discount->getTo();
        $model->percentage = $discount->getPercentage();
        $model->product = ProductModel::createFromProduct($discount->getProduct());

        return $model;
    }
}