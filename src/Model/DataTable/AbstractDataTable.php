<?php

namespace App\Model\DataTable;

use App\Model\DataTable\Column\AbstractColumn;
use App\Model\DataTable\Column\AbstractRecord;
use App\Repository\DataTable\AbstractDataTableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

abstract class AbstractDataTable
{
    private ArrayCollection $columns;
    private ArrayCollection $records;

    public abstract function create(): void;
    public abstract function getType(): string;

    public function __construct
    (
        protected readonly AuthorizationCheckerInterface $authorizationChecker
    )
    {
        $this->columns = new ArrayCollection();
        $this->records = new ArrayCollection();
    }

    public function getTable(): array
    {
        return [
            'columns' => $this->getColumns()->toArray(),
            'records' => $this->getRecords()->toArray(),
            'actions' => $this->getActions(),
            'bulkActions' => $this->getBulkActions(),
        ];
    }

    public function buildTable(AbstractDataTableRepository $repository): void
    {
        $this->create();
        $qb = $repository->getQueryBuilder();
        $objects = $qb->getQuery()->getResult();

        foreach ($objects as $object) {
            $this->addRecord($object);
        }

        //still need to add abstract column transforming
    }

    public function addColumn(string $name, ?string $dbField, array $options): void
    {
        $column = new AbstractColumn($name, $dbField);
        foreach ($options as $key => $option) {
            if ($key == 'isKey') {
                $column->setIsKey($option);
                continue;
            }

            $column->addOption($key, $option);
        }

        $this->columns->add($column);
    }

    public function addRecord(mixed $object): void
    {
        $record = new AbstractRecord();
        foreach ($this->getColumns() as $column) {
            $record->addValue($column->getName(),  $object[$column->getDbField()]);
        }

        $this->getRecords()->add($record);
    }

    private function toCamelCase($string): string
    {
        $result = ucwords(str_replace(['-', '_'], ' ', $string));
        $result = str_replace(' ', '', $result);

        return lcfirst($result);
    }

    public function getActions(): array
    {
        return match (true) {
            $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN') => ['edit', 'delete'],
            $this->authorizationChecker->isGranted('ROLE_ADMIN') => ['edit'],
            $this->authorizationChecker->isGranted('ROLE_USER') => [],
            default => [],
        };
    }

    public function getBulkActions(): array
    {
        return [];
    }

    /**
     * @return ArrayCollection<AbstractColumn>
     */
    public function getColumns(): ArrayCollection
    {
        return $this->columns;
    }

    /**
     * @return ArrayCollection<AbstractRecord>
     */
    public function getRecords(): ArrayCollection
    {
        return $this->records;
    }
}