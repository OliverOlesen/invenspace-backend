<?php

namespace App\Projector;

use App\Entity\Category;
use App\Entity\Product;
use App\Event\Category\CategoryCreated;
use App\Event\Category\CategorySelected;
use App\Event\Category\CategoryUpdated;
use App\Event\Product\ProductCreated;
use App\Event\Product\ProductUpdated;
use App\Model\CategoryModel;
use App\Model\ProductModel;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class ProductProjector extends AbstractProjector
{
    private ProductRepository $repository;
    public function __construct
    (
        EntityManagerInterface $entityManager,
        private readonly CategoryProjector $categoryProjector,
    )
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(Product::class);
    }

    public function ApplyProductInfoCreated(ProductCreated $event): Product|false
    {
        /** @var ProductModel $model */
        $model = $event->getData();

        if ($model instanceof ProductModel)
        {
            $product = (new Product());
            $this->setCommonProperties($product, $model, true);


            if ($model->category instanceof Category)
            {
                $contact = $this->categoryProjector->GetCategoryFromId($model->category);
                $product->setCategory($contact);
            }

            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();

            return $product;
        }
        return false;
    }

    public function ApplyProductUpdated(ProductUpdated $event): Product|false
    {
        /** @var ProductModel $model */
        $model = $event->getData();

        if ($model instanceof ProductModel)
        {
            $product = $this->repository->findOneByUuid($model->uuid);
            if (!$product) {
                return false;
            }

            $this->setCommonProperties($product, $model);

            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();

            return $product;
        }

        return false;
    }

    public function setCommonProperties(Product $product, ProductModel $model, bool $isCreate = false): void
    {
        if ($isCreate) {
            $product->setUuid(Uuid::uuid4()->toString());
        }

        $product->setPrice($model->price);
        $product->setStock($model->stock);
        $product->setName($model->name);
        $product->setImage($model->image);
        $product->setDescription($model->description);
    }
}