<?php

namespace App\Model\DataTable\Column;

use Doctrine\Common\Collections\ArrayCollection;

class AbstractRecord
{
    private ArrayCollection $values;

    public function __construct()
    {
        $this->values = new ArrayCollection();
    }

    public function getValues(): ArrayCollection
    {
        return $this->values;
    }

    public function addValue(string $key, mixed $value): void
    {
        $this->getValues()->set($key, $value);
    }
}