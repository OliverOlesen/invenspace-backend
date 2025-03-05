<?php

namespace App\Event;

use App\Model\AbstractModel;

class AbstractEvent
{
    private AbstractModel $data;

    public function __construct(AbstractModel $data) {
        $this->data = $data;
    }

    public function getData(): AbstractModel
    {
        return $this->data;
    }
}