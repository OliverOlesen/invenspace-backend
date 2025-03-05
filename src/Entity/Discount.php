<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Discount extends BaseDiscount
{
    #[ORM\OneToOne(targetEntity: Product::class)]
    private Product $product;

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

}