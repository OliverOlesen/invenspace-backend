<?php

namespace App\Model\DataTable\Column;

use Doctrine\Common\Collections\ArrayCollection;

class AbstractColumn
{
    private string $name;
    private ?string $dbField;
    private bool $isKey = false;
    private ArrayCollection $options;

    public function __construct(string $name, ?string $dbField, ArrayCollection $options = null)
    {
        $this->name = $name;
        $this->dbField = $dbField;
        $this->options = is_null($options) ? new ArrayCollection() : $options;

        return $this;
    }

    public function getOptions(): ArrayCollection
    {
        return $this->options;
    }

    public function setOptions(ArrayCollection $options): void
    {
        $this->options = $options;
    }

    public function addOption(string $key, mixed $value): void
    {
        $this->options->set($key, $value);
    }

    public function setIsKey(bool $isKey): void
    {
        $this->isKey = $isKey;
    }

    public function isKey(): bool
    {
        return $this->isKey;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): AbstractColumn
    {
        $this->name = $name;
        return $this;
    }

    public function getDbField(): ?string
    {
        return $this->dbField;
    }

    public function setDbField(?string $dbField): AbstractColumn
    {
        $this->dbField = $dbField;
        return $this;
    }
}