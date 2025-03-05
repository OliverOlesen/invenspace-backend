<?php

namespace App\Factory;

use App\Model\DataTable\AbstractDataTable;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DataTableFactory
{
    private iterable $dataTables;

    public function __construct
    (
        iterable $dataTables,
        protected readonly AuthorizationCheckerInterface $authorizationChecker
    )
    {
         $this->dataTables = $dataTables;
    }

    public function getDataTableForType(string $type): ?AbstractDataTable
    {
        foreach ($this->dataTables as $table) {
            if ($table->getType() === $type) {
                return new $table($this->authorizationChecker);
            }
        }
        return null;
    }
}