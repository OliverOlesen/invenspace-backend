<?php

namespace App\Entity;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

class AbstractLine extends AbstractEntity
{
    #[ORM\OneToOne(targetEntity: Product::class)]
    private Product $product;
    #[ORM\Column(type: 'integer')]
    private int $amount;

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): AbstractLine
    {
        $this->product = $product;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): AbstractLine
    {
        $this->amount = $amount;
        return $this;
    }
}