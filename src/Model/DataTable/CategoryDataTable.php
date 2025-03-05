<?php

namespace App\Model\DataTable;

use App\Entity\AbstractEntity;
use App\Entity\Category;
use App\Interface\DataTableInterface;
use App\Model\DataTable\AbstractDataTable;

class CategoryDataTable extends AbstractDataTable implements DataTableInterface
{
    function getType(): string
    {
        return Category::class;
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

        $this->addColumn('type', 'type', [
            'label' => 'type',
        ]);
    }

    public function getBulkActions(): array
    {
        return [];
    }
}