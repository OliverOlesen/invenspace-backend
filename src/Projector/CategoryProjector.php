<?php

namespace App\Projector;

use App\Entity\Category;
use App\Event\Category\CategoryCreated;
use App\Event\Category\CategorySelected;
use App\Event\Category\CategoryUpdated;
use App\Model\CategoryModel;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class CategoryProjector extends AbstractProjector
{
    private CategoryRepository $repository;
    public function __construct
    (
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(Category::class);
    }

    public function GetCategoryFromId(Category $category): Category|false
    {
        $category = $this->repository->findOneById($category->getId());
        if (!$category) {
            return false;
        }

        return $category;
    }

    public function ApplyCategoryCreated(CategoryCreated $event): Category|false
    {
        /** @var CategoryModel $model */
        $model = $event->getData();

        if ($model instanceof CategoryModel)
        {
            $category = (new Category());
            $this->setCommonProperties($category, $model, true);

            $this->getEntityManager()->persist($category);
            $this->getEntityManager()->flush();

            return $category;
        }

        return false;
    }

    public function ApplyCategoryUpdated(CategoryUpdated $event): Category|false
    {
        /** @var CategoryModel $model */
        $model = $event->getData();

        if ($model instanceof CategoryModel){
            $category = $this->repository->findOneByUuid($model->uuid);
            if (!$category) {
                return false;
            }

            $this->setCommonProperties($category, $model);

            $this->getEntityManager()->flush();

            return $category;
        }

        return  false;
    }

    public function setCommonProperties(Category $category, CategoryModel $model, bool $isCreate = false) : void
    {
        if ($isCreate) {
            $category->setUuid(Uuid::uuid4()->toString());
        }

        $category->setType($model->type);
        $category->setName($model->name);
        $category->setDescription($model->description);
        $category->setImage($model->image);
    }
}