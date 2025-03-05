<?php

namespace App\Entity;

use App\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;

#[ORM\Table(name: '`order`')]
#[ORM\Entity(repositoryClass: 'App\Repository\OrderRepository')]
class Order extends AbstractEntity
{
    #[ORM\OneToOne(targetEntity: Address::class)]
    private Address $shippingAddress;

    #[ORM\OneToOne(targetEntity: Address::class)]
    private Address $billingAddress;

    #[ORM\OneToOne(targetEntity: Contact::class)]
    private ?Contact $contact = null;

    #[ORM\Column(type: 'string')]
    private string $discountCode = '';

    #[ORM\OneToMany(targetEntity: OrderLine::class, mappedBy: 'order')]
    private \Doctrine\Common\Collections\Collection $items;

    public function __construct()
    {
        parent::__construct();
        $this->items = new ArrayCollection();
    }

    public function getShippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(Address $shippingAddress): Order
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    public function getBillingAddress(): Address
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(Address $billingAddress): Order
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): Order
    {
        $this->contact = $contact;
        return $this;
    }

    public function getDiscountCode(): string
    {
        return $this->discountCode;
    }

    public function setDiscountCode(string $discountCode): Order
    {
        $this->discountCode = $discountCode;
        return $this;
    }

    public function getItems(): \Doctrine\Common\Collections\Collection
    {
        return $this->items;
    }

    public function setItems(\Doctrine\Common\Collections\Collection $items): Order
    {
        $this->items = $items;
        return $this;
    }

    // Custom functions -------------------------

    public function hasDiscount(): bool
    {
        // TODO: Add functionality to check if there is a discount registered to the order
        // TODO: All discount stuff needs to be created first.
        return true;
    }

    public function getTotalItemsCost(): int
    {
        $totalCost = 0;

        /** @var OrderLine $item */
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

        /** @var OrderLine $item */
        foreach ($this->items as $item) {
            $quantity = $item->getAmount();
            $totalItems += $quantity;
        }

        return $totalItems;
    }
}