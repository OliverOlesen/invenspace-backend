<?php

namespace App\Entity;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\ProductRepository')]
class Product extends AbstractEntity
{
    #[ORM\OneToOne(targetEntity: Category::class)]
    private ?Category $category = null;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private int $stock;

    #[ORM\Column(type: 'string')]
    private string $name = '';

    #[ORM\Column(type: 'string')]
    private string $image = '';

    #[ORM\Column(type: 'string')]
    private string $description = '';

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): Product
    {
        $this->category = $category;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): Product
    {
        $this->stock = $stock;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): Product
    {
        $this->image = $image;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    public function hasDiscount(): bool {
        return true;
    }
}