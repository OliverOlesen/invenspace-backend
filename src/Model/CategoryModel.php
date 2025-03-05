<?php

namespace App\Model;

use App\Entity\Category;

class CategoryModel extends AbstractModel
{
    public string $type = '';
    public string $name = '';
    public string $description = '';
    public string $image = '';

    public static function createFromCategory(Category $category): self
    {
        $model = new self();
        $model->uuid = $category->getUuid();
        $model->type = $category->getType();
        $model->name = $category->getName();
        $model->description = $category->getDescription();
        $model->image = $category->getImage();

        return $model;
    }
}