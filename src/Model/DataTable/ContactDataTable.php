<?php

namespace App\Model\DataTable;

use App\Entity\AbstractEntity;
use App\Entity\Contact;
use App\Interface\DataTableInterface;
use App\Model\DataTable\AbstractDataTable;

class ContactDataTable extends AbstractDataTable implements DataTableInterface
{
    function getType(): string
    {
        return Contact::class;
    }

    public function create(): void
    {
        $this->addColumn('uuid', 'uuid', [
            'isKey' => true
        ]);

        $this->addColumn('first name', 'firstName', [
            'label' => 'first_name',
        ]);

        $this->addColumn('middle name', 'middleName', [
            'label' => 'middle_name',
        ]);

        $this->addColumn('last name', 'lastName', [
            'label' => 'last_name',
        ]);

        $this->addColumn('email', 'email', [
            'label' => 'email',
        ]);

        $this->addColumn('mobile', 'mobile', [
            'label' => 'mobile',
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