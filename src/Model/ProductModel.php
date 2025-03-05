<?php

namespace App\Model;

use App\Entity\Category;
use App\Entity\Product;

class ProductModel extends AbstractModel
{
    public float $price;
    public int $stock;
    public string $name = '';
    public string $image = '';
    public string $description = '';

    public ?Category $category = null;

    public static function createFromProduct(Product $productInfo): self
    {
        $model = new self();
        $model->uuid = $productInfo->getUuid();
        $model->price = $productInfo->getPrice();
        $model->stock = $productInfo->getStock();
        $model->name = $productInfo->getName();
        $model->image = $productInfo->getImage();
        $model->description = $productInfo->getDescription();

        return $model;
    }
}