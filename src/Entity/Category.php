<?php

namespace App\Entity;

use App\Model\CategoryModel;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Self_;

#[ORM\Entity(repositoryClass: 'App\Repository\CategoryRepository')]
class Category extends AbstractEntity
{
    #[ORM\Column(type: 'string')]
    private string $type = '';

    #[ORM\Column(type: 'string')]
    private string $name = '';

    #[ORM\Column(type: 'string')]
    private string $description = '';

    #[ORM\Column(type: 'string')]
    private string $image = '';

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Category
    {
        $this->type = $type;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Category
    {
        $this->description = $description;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): Category
    {
        $this->image = $image;
        return $this;
    }

    // TODO: Still don't think the createFromModel should be on the entity
    public static function createFromCategoryModel(CategoryModel $model): self
    {
        $category = new self();
        $category->setType($model->type);
        $category->setName($model->name);
        $category->setDescription($model->description);
        $category->setImage($model->image);

        return $category;
    }

}