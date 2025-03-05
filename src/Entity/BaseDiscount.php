<?php

namespace App\Entity;

use DateTime;
use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap(['baseDiscount' => BaseDiscount::class, 'discount' => Discount::class, 'discountCode' => DiscountCode::class])]
class BaseDiscount extends AbstractEntity
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $from;

    #[ORM\Column(type: 'datetime')]
    private DateTime $to;

    #[ORM\Column(type: 'integer')]
    private int $percentage = 0;

    public function getFrom(): DateTime
    {
        return $this->from;
    }

    public function setFrom(DateTime $from): BaseDiscount
    {
        $this->from = $from;
        return $this;
    }

    public function getTo(): DateTime
    {
        return $this->to;
    }

    public function setTo(DateTime $to): BaseDiscount
    {
        $this->to = $to;
        return $this;
    }

    public function getPercentage(): int
    {
        return $this->percentage;
    }

    public function setPercentage(int $percentage): BaseDiscount
    {
        $this->percentage = $percentage;
        return $this;
    }

}