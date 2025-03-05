<?php

namespace App\Model;

use App\Entity\BaseDiscount;
use DateTime;

/* BaseDiscount is a discount for the shop wide. */
class BaseDiscountModel extends AbstractModel
{
    public DateTime $from;
    public DateTime $to;
    public int $percentage;

    // TODO: Can discr be null here? since this is the way in which it's controlled what type of discount it is (product based, code based, etc..) should this be done in the model as well?
    // TODO: public ?string $discr;
    // TODO: public string $code = '';

    public static function CreateFromBaseDiscount(BaseDiscount $baseDiscount): self
    {
        $model = new self();
        $model->from = $baseDiscount->getFrom();
        $model->to = $baseDiscount->getTo();
        $model->percentage = $baseDiscount->getPercentage();

        return $model;
    }
}