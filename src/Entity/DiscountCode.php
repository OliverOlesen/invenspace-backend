<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DiscountCode extends BaseDiscount
{
    #[ORM\Column(type: 'string')]
    private string $code = '';

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): DiscountCode
    {
        $this->code = $code;
        return $this;
    }
}