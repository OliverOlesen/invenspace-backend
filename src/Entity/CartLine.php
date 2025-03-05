<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\CartLineRepository')]
class CartLine extends AbstractLine
{
    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    private Cart $cart;

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): CartLine
    {
        $this->cart = $cart;
        return $this;
    }


}