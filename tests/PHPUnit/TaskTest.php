<?php

namespace PHPUnit;

use DtoTester\DtoTest;
use Pake\Task;

class TaskTest extends DtoTest
{
    protected function getInstance()
    {
        return new Task(
            'name', function () {
                return 'foobar';
            }, 'description'
        );
    }

    public function testCanBeCreatedWithoutDescription()
    {
        $this->assertNull(
            (new Task(
                'name', function () {
                    return 'foobar';
                }
            ))->description()
        );
    }
}
