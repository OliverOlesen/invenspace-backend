<?php

namespace App\Model\DataTable;

use App\Entity\AbstractEntity;
use App\Entity\Product;
use App\Interface\DataTableInterface;
use App\Model\DataTable\AbstractDataTable;

class ProductDataTable extends AbstractDataTable implements DataTableInterface
{

    function getType(): string
    {
        return Product::class;
    }

    public function create(): void
    {
        $this->addColumn('uuid', 'uuid', [
            'isKey' => true
        ]);

        $this->addColumn('name', 'name', [
            'label' => 'name',
        ]);

        $this->addColumn('description', 'description', [
            'label' => 'description',
        ]);

        $this->addColumn('image', 'image', [
            'label' => 'image',
        ]);

        $this->addColumn('price', 'price', [
            'label' => 'price',
        ]);

        $this->addColumn('stock', 'stock', [
            'label' => 'stock',
        ]);
    }

    public function getActions(): array
    {
        return match (true) {
            $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN') => ['edit'],
            $this->authorizationChecker->isGranted('ROLE_ADMIN') => ['edit'],
            $this->authorizationChecker->isGranted('ROLE_USER') => [],
            default => [],
        };
    }

    public function getBulkActions(): array
    {
        return [];
    }
}