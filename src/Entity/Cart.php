<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;

#[ORM\Entity(repositoryClass: 'App\Repository\CartRepository')]
class Cart extends AbstractEntity
{
    #[ORM\OneToOne(targetEntity: User::class)]
    private ?User $user;

    #[ORM\OneToMany(targetEntity: CartLine::class, mappedBy: 'cart', cascade: ['remove'])]
    private ArrayCollection|CartLine|null $items;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getItems(): ArrayCollection|CartLine|null
    {
        return $this->items;
    }

    public function setItems(ArrayCollection|CartLine|null $items): Cart
    {
        $this->items = $items;
        return $this;
    }

    // ---------------- Custom methods

    public function getTotalItemsCost(): int
    {
        $totalCost = 0;

        /** @var CartLine $item */
        foreach ($this->items as $item) {
            $price = $item->getProduct()->getPrice();
            $quantity = $item->getAmount();
            $totalCost += $price * $quantity;
        }

        return $totalCost;
    }

    public function getTotalItemsAmount(): int
    {
        $totalItems = 0;

        /** @var CartLine $item */
        foreach ($this->items as $item) {
            $quantity = $item->getAmount();
            $totalItems += $quantity;
        }

        return $totalItems;
    }

}