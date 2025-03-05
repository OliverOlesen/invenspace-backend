<?php

namespace App\Model\DataTable;

use App\Entity\AbstractEntity;
use App\Entity\User;
use App\Interface\DataTableInterface;

class UserDataTable extends AbstractDataTable implements DataTableInterface
{

    function getType(): string
    {
        return User::class;
    }

    public function create(): void
    {
        $this->addColumn('uuid', 'uuid', [
            'isKey' => true
        ]);

        $this->addColumn('username', 'username', [
            'label' => 'username',
        ]);
        $this->addColumn('email', 'email', [
            'label' => 'email',
        ]);
    }

    public function getBulkActions(): array
    {
        return [];
    }
}