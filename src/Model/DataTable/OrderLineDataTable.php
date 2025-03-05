<?php

namespace App\Model\DataTable;

use App\Entity\OrderLine;
use App\Interface\DataTableInterface;
use App\Model\DataTable\AbstractDataTable;

class OrderLineDataTable extends AbstractDataTable implements DataTableInterface
{
    function getType(): string
    {
        return OrderLine::class;
    }

    public function create(): void
    {
        $this->addColumn('uuid', 'uuid', [
            'isKey' => true
        ]);

        $this->addColumn('id', 'id', [
            'label' => 'id',
        ]);

        $this->addColumn('createdAt', 'createdAt', [
            'label' => 'createdAt',
        ]);

        $this->addColumn('product', 'product', [
            'label' => 'product',
        ]);

        $this->addColumn('price', 'price', [
            'label' => 'price',
        ]);
    }

    public function getActions(): array
    {
        return [
        ];
    }

    public function getBulkActions(): array
    {
        return [];
    }
}