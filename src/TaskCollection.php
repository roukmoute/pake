<?php

declare(strict_types=1);

namespace Pake;

class TaskCollection extends Map
{
    public function __construct()
    {
        parent::__construct('int', Task::class);
    }

    public function current(): Task
    {
        return parent::current();
    }

    public function offsetGet($index): Task
    {
        return parent::offsetGet($index);
    }
}
